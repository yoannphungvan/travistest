<?php

/**
 * Middleware to pull out raw filters and instiate filters class.
 */
use Symfony\Component\HttpFoundation\Request;

$app->before(function (Request $request) use ($app) {
    $app['filters'] = $app['request']->get('filter', array());
});
