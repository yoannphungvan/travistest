<?php

/* ---------------------------------------------------------
 * src/be/controllers/UsersController.php
 *
 * Users Controller.
 *
 * Copyright 2015 - TEST
 * ---------------------------------------------------------*/

namespace TEST\Controllers

{
    use Silex\Application;
    use TEST\Exceptions\BadRequestException;

    /**
     * Handles the users.
     **/
    class UserController extends RestController
    {
        /**
         * Constructor.
         **/
        public function __construct(Application $app, $filters = null)
        {
            $this->manager = 'UserManager';
            $this->objectCacheTag = 'users';
            $this->useCache = false;
            parent::__construct($app, $filters);
        }
    }
}
