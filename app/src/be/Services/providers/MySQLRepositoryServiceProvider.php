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

class MySQLRepositoryServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['database'] = $app->share(function ($app) {
            return new MySQLRepository(
                $app['database.options']['host'],
                $app['database.options']['username'],
                $app['database.options']['password'],
                $app['database.options']['db'],
                $app['database.options']['port']
            );
        });
    }

    public function boot(Application $app)
    {
    }
}
