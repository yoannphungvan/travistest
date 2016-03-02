<?php

/* ---------------------------------------------------------
 * src/be/models/entities/User.php
 *
 * A user.
 *
 * Copyright 2015 - TEST
 * ---------------------------------------------------------*/

namespace TEST\Models\Entities;

/**
 * A user.
 **/
class Users extends BaseEntity
{
    /**
     * @var $id
     */
    public $id;

    /**
     * @var $name
     */
    public $name;

    /**
     * @var array $fields A fields definition
     **/
    public static $settableFields = [
        'id'  => [],
        'name'  => []
    ];

    /**
     * @var array $fields A fields definition
     **/
    public static $gettableFields = [
        'id'  => [],
        'name'  => []
    ];
}

