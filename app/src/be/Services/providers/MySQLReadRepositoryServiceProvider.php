<?php

/**
 * Silex service provider for instantiating a MySQL repo wrapper.
 *
 * Copyright 2015 - TEST
 */

namespace TEST\Services\Providers;

use Silex\Application;
use TEST\Services\MySQLRepository;
use Silex\ServiceProviderInterface;

class MySQLReadRepositoryServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['db_read'] = $app->share(function ($app) {
            return new MySQLRepository(
                $app['db.read.options']['host'],
                $app['db.read.options']['username'],
                $app['db.read.options']['password'],
                $app['db.read.options']['db'],
                $app['db.read.options']['port']
            );
        });
    }

    public function boot(Application $app)
    {
    }
}
