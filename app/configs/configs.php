<?php

/* ---------------------------------------------------------
 * configs/configs.sample.php
 *
 * Sample configurations.
 *
 * Copyright 2015 - WORDR
 * ---------------------------------------------------------*/

// Debug mode disabled
$app['debug'] = true;

// Twig cache
$app['twig.options.cache'] = false;

// Redis cache
$app['predis.host'] = '4.4.4.17';
$app['predis.port'] = '6379';

// Local
$app['locale'] = 'en_US';
$app['domain'] = 'en';
$app['charset'] = 'UTF-8';

// MySQL Configs
$app['mysql.host'] = 'srv1.db.dev.salesfloor.net';
$app['mysql.username'] = 'root';
$app['mysql.password'] = '123456';
$app['mysql.port'] = '3306';
$app['mysql.db'] = 'travis';

// Monolog
$app['monolog.name'] = 'app';
$app['monolog.level'] = Monolog\Logger::DEBUG;
$app['monolog.logfile'] = PATH_LOGS . '/' . $app['monolog.name'] . '.log';

$app['monolog.session_id'] = uniqid(rand());

if (!defined('CORS_ALLOW_ORIGIN')) {
    DEFINE('CORS_ALLOW_ORIGIN', '*');
}



