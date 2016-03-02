<?php

use TEST\Controllers;

$app['controller.user'] = $app->share(function () use ($app) {
    return Controllers\ControllerHelper::createController($app, 'User');
});
