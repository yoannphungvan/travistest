<?php

/* ---------------------------------------------------------
 * web/app.php
 *
 * Entry point for api.ssense.com.
 *
 * Copyright 2015 - TEST
 * ---------------------------------------------------------*/

use TEST\Models\Managers\AppProxy;

require_once '../configs/paths.php';

// Loading auto-loader
require_once PATH_VENDOR . '/autoload.php';

// Creating the application
$app = new Silex\Application();

// Setting configurations
require PATH_CONFIGS . '/configs.php';

// Init the singleton for the App
AppProxy::getInstance($app);

// Loading Middlewares
require PATH_SRC . '/middlewareLoader.php';

// Loading services
require PATH_SRC . '/services.php';

// Defining the routes
require PATH_CONFIGS . '/routes.php';

// Load controller definitions
require PATH_SRC . '/controllers.php';


return $app;
