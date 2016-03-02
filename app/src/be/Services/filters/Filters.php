<?php

namespace TEST\Services\Filters

{

    use TEST\Models\Entities\Gender;

    class Filters implements \arrayaccess
    {

        /**
         * Filters
         * @var array
         */
        protected $__filters = [];

        /**
         * Constructor - Set the initial filters
         * @param array $filters
         */
        public function __construct(array $filters)
        {
            $this->__filters = $filters;
        }

        public function build($filters = null)
        {
            if (!is_null($filters)) {
                $this->__filters = $filters;
            }

            $this->setFilters();

            return $this;
        }

        /**
         * Keep only valid filters and set defaut value if applies
         */
        protected function setFilters()
        {
        }

        /**
         * Return all the filters, as an array
         * @return array
         */
        public function toArray()
        {
            return $this->__filters;
        }

        /**
         * Remove a specific filter
         * @param $key
         */
        public function remove($key)
        {
            if ($this->has($key)) {
                unset( $this->__filters[ $key ] );
            }
        }

        /*
         * Remove all filters
         */
        public function reset()
        {
            foreach ($this->__filters as $key => $value) {
                $this->remove($key);
            }
        }

        /**
         * Return if a key is available in the filters
         *
         * @param string $key
         * @return bool
         */
        public function has($key)
        {
            if (array_key_exists($key, $this->__filters)) {
                return true;
            } else {
                return false;
            }
        }

        /**
         * Get one of the filter value
         *
         * @param $key
         * @return mixed
         */
        public function __get($key)
        {
            if ($this->has($key)) {
                return $this->__filters[ $key ];
            }
        }

        /**
         * Add or update a filter
         *
         * @param $key
         * @param $value
         */
        public function __set($key, $value)
        {
            $this->__filters[ $key ] = $value;
        }

        /**
         * Get one of the filter value
         *
         * @param $key
         * @return mixed
         */
        public function offsetGet($key)
        {
            if ($this->has($key)) {
                return $this->__filters[ $key ];
            }
        }

        /**
         * Set a filter value
         *
         * @param string|integer $key Any valid array key
         * @param string|integer|boolean $value Any valid value except objects.
         *
         * @return void
         */
        public function offsetSet($key, $value)
        {
            $this->__filters[ $key ] = $value;
        }

        /**
         * Remove a key from the filters array
         *
         * @param string|integer $key Any valid array index
         *
         * @return void
         */
        public function offsetUnset($key)
        {
            unset( $this->__filters[ $key ] );
        }

        /**
         * Check if a certain filter exists
         *
         * @param string|integer $key Any valid array key
         *
         * @return boolean True|False based on wether the filter exists.
         */
        public function offsetExists($key)
        {
            return array_key_exists($key, $this->__filters);
        }

        public function setIfNotExist($key, $value)
        {
            if (!$this->has($key)) {
                $this->$key = $value;
            }
        }


        /**
         * Build a string out of the __filters array, mainly useful for logging
         * and debugging without needing var dump().
         *
         * @return string Returns the stringified filters array
         */
        public function toString()
        {
            $keyVals = [];
            foreach ($this->__filters as $key => $val) {
                if ($val === '') {
                    $val = '__EMPTY_STRING__';
                }
                if ($val === null) {
                    $val = '__NULL__';
                }
                $keyVals[] = $key . ': ' . $val;
            }
            return implode("\n", $keyVals);
        }
    }
}
