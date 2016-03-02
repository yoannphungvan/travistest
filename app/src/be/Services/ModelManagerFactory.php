<?php

/* ---------------------------------------------------------
 * src/be/services/ModelManagerFactory.php
 *
 * A model manager factory.
 *
 * Copyright 2015 - TEST
 * ---------------------------------------------------------*/

namespace TEST\Services {

    use TEST\Models\Entities\Predis;

    /**
     * A model manager factory.
     **/
    class ModelManagerFactory
    {
        /**
         * @var MySQLRepository
         */
        private $db;

        /**
         * @var RedisCache
         */
        private $cache;

        /**
         * Constructor
         *
         * @param MySQLRepository $db
         * @param RedisCache $cache
         */
        public function __construct(MySQLRepository $db, Predis $cache)
        {
            $this->db    = $db;
            $this->cache = $cache;
        }

        /**
         * Create a model manager.
         *
         * @param string $name     A manager name
         * @param Repository $repo A repository instance
         * @param Object $cache    A cache instance
         * @return BaseManager A manager instance
         **/
        public function create($name, $repo = null, $cache = null, $filters = array(), $manager = null)
        {
            $name = '\\TEST\\Models\\Managers\\' . $name;

            if (is_null($repo)) {
                $repo = $this->db;
            }

            if (is_null($cache)) {
                $cache = $this->cache;
            }

            return new $name($repo, $cache, $this, $filters, $manager);
        }
    }
}
