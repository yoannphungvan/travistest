<?php

namespace TEST\Services\Filters

{

    class FilterValidation
    {
        public function validate($filter, $filterName)
        {
            $valid = true;
            if (method_exists($this, 'validate' . $filterName)) {
                $valid = $this->{'validate' . $filterName}($filter);
            }

            if (!$valid) {
                throw new TEST\Exceptions\BadRequestException($filterName . ' filter passed with invalid value');
            }
        }

        protected function validateDesigner($filter)
        {
            return $filter['value'] !== '';
        }

        protected function getValidateFilterMethod($name)
        {
            $splitNames = explode('_', $name);

            foreach ($splitNames as $splitName) {
                $camelName[] = ucwords($splitName);
            }
            // Turn string into words to properly capitalize and then concatenate each word
            $capitalizedName = implode($camelName);

            return 'validate' . $capitalizedName;
        }
    }
}
