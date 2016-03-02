<?php

  /**
   * Main API entry point
   *
   * Copyright 2014 - Salesfloor
   */

  // Load autoload file
  require_once __DIR__ . '/../vendor/be/autoload.php';

  if (file_exists(__DIR__ . '/../../c3.php')) {
    include __DIR__ . '/../../c3.php';
  }

  $app = require_once __DIR__ . '/../src/be/app.php';

  $app->run();