<?php

/* ---------------------------------------------------------
 * src/be/controllers/ControllerHelper.php
 *
 * Controller Helper.
 *
 * Copyright 2015 - TEST
 * ---------------------------------------------------------*/

namespace TEST\Controllers

{
    use Silex\Application;

    /**
     * Controller Helper.
     **/
    class ControllerHelper
    {
        /**
         * Instantiate a controller with standard dependencies
         *
         * @param Application $app Instance of app
         * @param string $controllerName The name of the controller class
         *
         * @return BaseController
         */
        public static function createController(Application $app, $controllerName)
        {
            $controller = 'TEST\\Controllers\\' . $controllerName . 'Controller';

            /* TODO: inject various app services into the controller */

            return new $controller($app, $app['filters']);
        }
    }
}
