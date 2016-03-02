<?php

/* ---------------------------------------------------------
 * src/be/controllers/BaseController.php
 *
 * Base Controller.
 *
 * Copyright 2015 - TEST
 * ---------------------------------------------------------*/

namespace TEST\Controllers

{
    use Silex\Application;
    use TEST\Services\Filters\Filters;
    use TEST\Models\Managers;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpKernel\HttpKernelInterface;

    /**
     * Base controller.
     **/
    abstract class BaseController
    {
        const MANAGERS_NAMESPACE = 'TEST\\Models\\Managers\\';
        /**
         * @var string $manager A manager
         **/
        protected $manager;

        /**
         * @var
         */
        protected $modelManager;

        /**
         * @var Application
         */
        protected $app;

        /**
         * Holds an array of filters coming in from the current request.
         *
         * @var  TEST\Services\Filters
         */
        protected $filters = null;

        /**
         * Class constructor - Setup some settings that are used across the controller
         */
        public function __construct($app, $filters)
        {
            $this->app = $app;
            $this->filters = new Filters($filters);

            if ($this->manager) {
                $this->modelManager = $app['factories.model_manager']->create($this->manager, null, null, $filters);
            }
        }

        /**
         * Get a list of objects.
         *
         * @param Application $app An Application instance
         * @return string A JSON
         **/
        public function getList(Application $app)
        {
            $page = $app['request']->get('page');
            $perPage = $app['request']->get('per_page');

            $objects = $this->modelManager->getList($this->filters, $page, $perPage);

            return $objects;
        }

        /**
         * Return a model matching the given ID
         *
         * @param integer $id
         * @param array $filters
         * @return mixed
         */
        public function getById(Application $app, $id)
        {
            return $this->modelManager->getById($id, $this->filters->toArray());
        }

        /**
         * Get an object.
         *
         * @param integer $id An id
         * @return string A JSON
         **/
        public function getObject(Application $app, $id)
        {
            $object = $this->modelManager->getById($id, $this->filters->toArray());
            $this->assertObject($this->app, $id, $object);

            $this->maskValues($object);

            return $object;
        }

        /**
         * Send a request to a controller.
         *
         * @param string $uri uri to send request to
         * @param string $method the request method
         * @param array $parameters parameters of the request
         * @return mixed the content of the response
         **/
        public function requestController(Application $app, $uri, $method, $parameters)
        {
            $request = Request::create($uri, $method, $parameters);
            $response = $this->app->handle($request, HttpKernelInterface::SUB_REQUEST, false);

            return $response->getContent();
        }

        /**
         * [create description]
         * @param  Application $app [description]
         * @return [type]           [description]
         */
        public function create(Application $app)
        {
            $fields = $this->getFieldsFromRequest($app, $this->modelManager);
            $object = $this->modelManager->create($fields);
            $id = $this->modelManager->persist($object);
            $object->id = $id;
            $this->maskValues($object);

            return $object;
        }

        /**
         * Update an object.
         *
         * @param Application $app An Application instance
         * @param integer $id An id
         * @return string A JSON
         **/
        public function update(Application $app, $id)
        {
            $fields = $this->getFieldsFromRequest($app, $this->modelManager);
            $object = $this->modelManager->getById($id);

            $this->assertObject($app, $id, $object);
            $object = $this->modelManager->prepareUpdate($object, $fields);
            $this->modelManager->persist($object);
            $this->maskValues($object);

            return $object;
        }

        /**
         * Delete an object.
         *
         * @param Application $app An Application instance
         * @param integer $id An id
         * @return string A JSON
         **/
        public function delete(Application $app, $id)
        {
            $object = $this->modelManager->getById($id);
            $this->assertObject($app, $id, $object);

            $this->modelManager->delete($id);

            $this->maskValues($object);

            return $object;
        }

        /**
         * Mask values before returning.
         *
         * @param Object $object An object
         **/
        protected function maskValues(&$object)
        {
        }


        /**
         * Parse a request to get the corresponding fields
         * Ignores fields that are not set in the request
         *
         * @param Application $app An Application instance
         * @param AddressManager $addressManager An AddressManager instance
         * @return array An associative array of set fields, of the form column_name => value
         **/
        public function getFieldsFromRequest(Application $app, $manager)
        {
            $fields = [];
            $settableFields = $manager->getSettableFields();

            foreach ($settableFields as $field => $option) {
                if (($value = $app['request']->get($field)) !== null) {
                    $fields[$field] = $value;
                }
            }

            return $fields;
        }

        protected function assertObject($app, $id, $object)
        {
            if (!isset($object)) {
                return $app->abort(404, "Object id '$id' from " . $this->manager . " does not exist");
            }
        }

        /**
         * try to get requested objects from redis
         *
         * @param  Application $app An Application instance
         * @param  String $objectsName the name of objects we can to get ex: categories, brands, products
         * @return String A $filters an array of filters
         */
        public function getFromRedis(Application $app, $objectsName)
        {
            $key = $this->getRedisKey($app, $objectsName, $this->filters->toArray());

            if ($app['cache']->exists($key)) {
                return $app['cache']->get($key);
            } else {
                return false;
            }

        }


        /**
         * Function to check for the presence of the magic cache buster
         * value
         *
         * @param Application $app Instance of current Silex\Application
         * @return boolean Returns true if the value is present
         */
        public function hasCacheBusterMagicValue(Application $app)
        {
            $cacheBusterMagicValue = $app['request']->get('cache_buster_magic_value');
            return $cacheBusterMagicValue === $app['cache_bust_magic_value']
                ? true
                : false;
        }


        /**
         * Transform the gender receive in the request
         *
         * @param  Application $app An Application instance
         * @param  String $gender A gender
         * @return String A switched gender
         */
        public function getRedisKey(Application $app, $objectsName, $baseFilters = array())
        {
            // SUPER MEGA BASTARD HACK FOR CACHE BUSTING
            // If we detect the magic value in the request
            // we pretend the redis key isn't there and let
            // the controller call the DB to refresh the cache and
            // TTL on the right key.
            if ($this->hasCacheBusterMagicValue($app)) {
                return false;
            }

            $filters = $this->filters->toArray();

            $page = $app['request']->get('page');
            $perPage = $app['request']->get('per_page');

            $redisKeyElements = array();

            if ($page && !is_null($page)) {
                $filters['page'] = $page;

            }
            if ($perPage && !is_null($perPage)) {
                $filters['per_page'] = $perPage;
            }

            $filters = array_merge($baseFilters, $filters);

            foreach ($filters as $key => $value) {
                $redisKeyElements[] = $key;
                $redisKeyElements[] = $value;
            }

            $redisKey = $objectsName . (sizeof($filters) > 0 ? ':' : '' ) . implode(':', $redisKeyElements);

            return $redisKey;
        }

        /**
         * Set data structure in redis
         *
         * @param  Application $app An Application instance
         * @param  String $objectName The name of the object we want to store in Redis
         * @param  Array $baseFilters An array of filters
         * @param  Array $object The value associated to the redis key ($objectName)
         * @return void
         */
        public function setInRedis(Application $app, $objectsName, $baseFilters, $objects, $ttl = null)
        {
            $key = $this->getRedisKey($app, $objectsName, $baseFilters);

            $app['cache']->set($key, $objects);
            if ($app['cache']->exists($key)) {
                // if $ttl given, then $ttl takes precedence over the config TTL
                // This allow us to use the same $ttl for several objects of the same family
                // Example: @see SEOController
                if (isset($ttl) && is_numeric($ttl)) {
                    $app['cache']->expire($key, $ttl);
                } else if (isset($app[$objectsName . '_ttl'])) {
                    $app['cache']->expire($key, $app[$objectsName . '_ttl']);
                }
            }
        }

        /**
         * Get value from cache with property 'useCache' check.
         *
         * This method is a wrapper for "getFromRedis".
         * Centralizes redundant validation for $this->useCache.
         *
         * @param  String $cacheKey Cache key name
         * @param  array  $filters The filters to apply
         * @return mixed
         */
        public function getFromCache($cacheKey, $filters)
        {
            $value = null;

            if ($this->useCache) {
                $value = $this->getFromRedis($this->app, $cacheKey, $filters);
            }

            return $value;
        }

        /**
         * Set value in cache with property 'useCache' check.
         *
         * This method is a wrapper for "setInRedis".
         * Centralizes redundant validation for $this->useCache.
         *
         * @param  String $cacheKey Cache key name
         * @param  array  $filters The filters to apply
         * @param  String $value The value associated to the cache key
         * @param  Int    $ttl The cache key time to live
         * @return mixed
         */
        public function setInCache($cacheKey, $filters, $value, $ttl = null)
        {
            if ($this->useCache) {
                $this->setInRedis($this->app, $cacheKey, $filters, $value, $ttl);
            }
        }
    }
}
