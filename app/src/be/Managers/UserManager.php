<?php
/* ---------------------------------------------------------
 * src/be/models/managers/UserManager.php
 *
 * A user manager.
 *
 * Copyright 2015 - TEST
 * --------------------------------------------------------- */

namespace TEST\Models\Managers;

use TEST\Exceptions\BadRequestException;
use TEST\Exceptions\UnauthorizedException;
use TEST\Helpers\UserHelper;
use Symfony\Component\Security\Core\User\InMemoryUserProvider;

/**
 * A user manager.
 * */
class UserManager extends BaseManager
{
    /**
     * Constructor.
     *
     * @param Repository $repo A repository
     * @param Object $cache A cache
     * @param ModelManagerFactory $managerFactory A manager factory
     * */
    public function __construct($repo, $cache, $managerFactory, $filters = array())
    {
        $this->table = 'users';
        $this->table_prefix = 'u';
        $this->entity = 'TEST\Models\Entities\Users';

        parent::__construct($repo, $cache, $managerFactory, $filters);
    }
}

