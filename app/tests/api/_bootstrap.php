<?php
// Here you can initialize variables that will be available to your tests

function fuck_you_codeception () {
  $app = new \Silex\Application();
  require_once __DIR__ . '/../../src/be/paths.php';
  require PATH_CONFIG . '/configs.php';

  // PHPUnit throws an exception if an included file doesn't exist. Pshhh.
  try { @include PATH_CONFIG . "/common/test-default.php"; } catch (\Exception $e) {}
  try { @include PATH_CONFIG . "/{$app['env']}/test-common.php"; } catch (\Exception $e) {}
  if ($app['retailer.brand']) {
    try { @include PATH_CONFIG . "/common/{$app['retailers.current']}/test-common.php"; } catch (\Exception $e) {}
    try { @include PATH_CONFIG . "/common/{$app['retailers.current']}/test-{$app['retailer.brand']}.php"; } catch (\Exception $e) {}
    try { @include PATH_CONFIG . "/{$app['env']}/{$app['retailers.current']}/test-common.php"; } catch (\Exception $e) {}
    try { @include PATH_CONFIG . "/{$app['env']}/{$app['retailers.current']}/test-{$app['retailer.brand']}.php"; } catch (\Exception $e) {}
  } else {
    try { @include PATH_CONFIG . "/common/test-{$app['retailers.current']}.php"; } catch (\Exception $e) {}
    try { @include PATH_CONFIG . "/{$app['env']}/test-{$app['retailers.current']}.php"; } catch (\Exception $e) {}
  }

  $app['ppp'] = 'was here';

  return $app;
}

$app = fuck_you_codeception();

define('URL', $app['test.url']);
define('STORE_NAME', $app['test.store_name']);
define('STORE_ID', $app['test.store_id']);
define('REGGIE_ID', $app['test.reggie_id']);
define('REPS_HAVE_SPECIALTIES', $app['retailer.reps_have_specialties']);
define('TEST_AVATAR_A', 'http://res.cloudinary.com/salesfloor-net/image/upload/a_exif/v1450458255/saks/allieprice');
define('TEST_AVATAR_B', 'http://res.cloudinary.com/salesfloor-net/image/upload/a_exif/v1450458255/saks/yuyan');

// If we ever need to prepare the database before tests,
// here's how it can be done. We don't need to yet, so
// I left it in comments.
//
//// open a connection to the db
//$connectionParams = [
//  'host'          => $app['mysql.host'],
//  'user'          => $app['mysql.username'],
//  'password'      => $app['mysql.password'],
//  'dbname'        => $app['mysql.db'],
//  'port'          => $app['mysql.port'],
//  'charset'       => 'utf8',
//  'driverClass'   => '\Doctrine\DBAL\Driver\Mysqli\Driver',
//  'driverOptions' => [MYSQLI_OPT_LOCAL_INFILE => true],
//];
//$dbal = \Doctrine\DBAL\DriverManager::getConnection($connectionParams);
//$mysqli = $dbal->getWrappedConnection()->getWrappedResourceHandle();
//
//$queries = require __DIR__ . '/fixtures/transactions.php';
//foreach ($queries as $q) {
//  $mysqli->query($q);
//}
