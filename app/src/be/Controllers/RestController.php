<?php

/**
 * Base class for all generic REST based controllers
 *
 * Copyright 2015 - TEST
 */

namespace TEST\Controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use TEST\Exceptions\BadRequestException;

/**
 * Restful controller implementing standard get,post,update,delete HTTP functionality
 */
class RestController extends BaseController
{
    protected $postValidationGroups = [];
    protected $putValidationGroups = [];
    protected $useCache = false;
    protected $objectCacheTag = '';

    /**
     * Get a list of all models, json encoded. Results are cached in redis based on the $useCache parameters
     *
     * @param Application $app An instance of the app
     * @return string|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getList(Application $app)
    {
        if ($this->useCache) {
            $cachedList = parent::getFromRedis($app, $this->objectCacheTag, $this->filters->toArray());
            if ($cachedList) {
                return $cachedList;
            } else {
                $objects = parent::getList($app);
                $list = $this->app->json($objects);
                parent::setInRedis($app, $this->objectCacheTag, $this->filters->toArray(), $list);
                return $list;
            }
        } else {
            return $this->responseFormat(parent::getList($app));
        }
    }

    /**
     * Returns a single model by ID
     *
     * @param Application $app An of the current app.
     * @param string $id The id of the model to fetch.
     *
     * @return string|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getOne(Application $app, $id)
    {
        $model = parent::getById($app, $id);
        return $this->responseFormat((array) $model);
    }

    /**
     * Route handler for creating new models.
     *
     * @param Application $app An instance of the app
     *
     * @return string|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function post(Application $app)
    {
        $object = parent::create($app);
        return $this->responseFormat((array) $object);
    }

    /**
     * Route handler for updating a model record in the DB.
     *
     * @param Application $app An instance of app.
     * @param Request $request An instance of incoming request
     *
     * @return string|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function put(Application $app, $id)
    {
        $object = parent::update($app, $id);
        return $this->responseFormat($object);
    }

    /**
     * Delete an object based on the ID
     *
     * @param Application $app
     * @param int $id
     *
     * @return string|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function delete(Application $app, $id)
    {
        $object = parent::delete($app, $id);
        return $this->responseFormat($object);
    }

    /**
     * Delete an object based on the ID
     *
     * @param Application $app
     * @param int $id
     *
     * @return string|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function responseFormat($data)
    {
        if ($data instanceOf \Exception) {
            $response = [
                'status' => 'error' ,
                'code'   => $data->getCode(),
                'response' => $data->getMessage()
            ];
        } else {
            $response = [
                'status' => 'ok' ,
                'code'   => '200',
                'response' => $data
            ];
        }

        return $this->app->json($response);
    }
}
