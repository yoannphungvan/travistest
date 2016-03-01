<?php

/**
 * Queries to setup dummy transactions to test cancellations & returns
 *
 * This program can either be used from a php script, where it returns a list of queries, e.g.
 *
 *    $queries = require __DIR__ . '/fixtures/transactions.php';
 *    foreach ($queries as $q) {
 *      $mysqli->query($q);
 *    }
 *
 * or it can be called from the command line, where it outputs SQL to STDOUT, e.g.
 *
 *    $ php app/tests/api/fixtures/transactions.php saks-dev | mysql --profile=saks-dev
 */

require_once __DIR__ . '/../../../vendor/be/autoload.php';
require_once __DIR__ . '/../_bootstrap.php';

$t0 = gmdate("Y-m-d H:i:s", strtotime("23 days ago"));
$t1 = gmdate("Y-m-d H:i:s", strtotime("8 days ago"));
$t2 = gmdate("Y-m-d H:i:s", strtotime("1 days ago"));
$t3 = gmdate("Y-m-d H:i:s", strtotime("0 days ago"));

$queries = [
  "DELETE FROM sf_rep_transaction        WHERE trx_id LIKE '90000000000000000%'",
  "DELETE FROM sf_rep_transaction_detail WHERE trx_id LIKE '90000000000000000%'",
  "INSERT INTO sf_rep_transaction (user_id, trx_id, trx_date, trx_type, trx_apply_total, trx_total, status, received_date) VALUES
    ({$app['test.reggie_id']}, '9000000000000000001', '$t0', 'script-callback',  1234.56,  1234.56, 1, '$t1') -- ordinary purchase
  , ({$app['test.reggie_id']}, '9000000000000000002', '$t0', 'script-callback',  2345.67,  2345.67, 1, '$t1') -- cancelled
  , ({$app['test.reggie_id']}, '9000000000000000002', '$t2', 'cancellation',    -2345.67, -2345.67, 1, '$t2')
  , ({$app['test.reggie_id']}, '9000000000000000003', '$t0', 'script-callback',  3456.78,  3456.78, 1, '$t1') -- partially returned
  , ({$app['test.reggie_id']}, '9000000000000000003', '$t2', 'return',          -3000.00, -3000.00, 1, '$t3')
  , ({$app['test.reggie_id']}, '9000000000000000004', '$t0', 'script-callback',  4567.89,  4567.89, 1, '$t1') -- fully returned
  , ({$app['test.reggie_id']}, '9000000000000000004', '$t2', 'return',          -4567.89, -4567.89, 1, '$t3')
  , ({$app['test.reggie_id']}, '9000000000000000005', '$t0', 'script-callback',  5678.90,  5678.90, 1, '$t1') -- fully returned, in multiple parts
  , ({$app['test.reggie_id']}, '9000000000000000005', '$t1', 'return',          -4000.00, -4000.00, 1, '$t2')
  , ({$app['test.reggie_id']}, '9000000000000000005', '$t2', 'return',          -1678.90, -1678.90, 1, '$t3')
  , ({$app['test.reggie_id']}, '9000000000000000006', '$t0', 'script-callback',  6789.01,  6789.01, 1, '$t1') -- partially returned, in multiple parts
  , ({$app['test.reggie_id']}, '9000000000000000006', '$t1', 'return',          -1789.01, -1789.01, 1, '$t3')
  , ({$app['test.reggie_id']}, '9000000000000000006', '$t2', 'return',          -2000.00, -2000.00, 1, '$t3')
  , ({$app['test.reggie_id']}, '9000000000000000007', '$t0', 'script-callback',  7890.12,  7890.12, 1, '$t1') -- date of cancel unknown
  , ({$app['test.reggie_id']}, '9000000000000000007', '$t2', 'cancellation',    -7890.12, -7890.12, 3, '$t2')
  , ({$app['test.reggie_id']}, '9000000000000000008', '$t0', 'script-callback',  4567.89,  4567.89, 1, '$t1') -- fully returned, date unknown
  , ({$app['test.reggie_id']}, '9000000000000000008', '$t2', 'return',          -4567.89, -4567.89, 3, '$t2')
  , ({$app['test.reggie_id']}, '9000000000000000009', '$t0', 'script-callback',  5678.90,  5678.90, 1, '$t1') -- fully returned, in multiple parts, all dates unknown
  , ({$app['test.reggie_id']}, '9000000000000000009', '$t1', 'return',          -4000.00, -4000.00, 3, '$t2')
  , ({$app['test.reggie_id']}, '9000000000000000009', '$t2', 'return',          -1678.90, -1678.90, 3, '$t2')
  , ({$app['test.reggie_id']}, '9000000000000000010', '$t0', 'script-callback',  5678.90,  5678.90, 1, '$t1') -- fully returned, in multiple parts, one date unknown
  , ({$app['test.reggie_id']}, '9000000000000000010', '$t1', 'return',          -5000.00, -5000.00, 3, '$t3')
  , ({$app['test.reggie_id']}, '9000000000000000010', '$t2', 'return',           -678.90,  -678.90, 1, '$t3')
  , ({$app['test.reggie_id']}, '9000000000000000011', '$t0', 'script-callback',  3456.78,  3456.78, 1, '$t1') -- partially returned, date unkown
  , ({$app['test.reggie_id']}, '9000000000000000011', '$t2', 'return',          -3000.00, -3000.00, 3, '$t2')
  , ({$app['test.reggie_id']}, '9000000000000000012', '$t0', 'script-callback',  6789.01,  6789.01, 1, '$t1') -- partially returned, in multiple parts, all dates unknown
  , ({$app['test.reggie_id']}, '9000000000000000012', '$t2', 'return',          -2000.00, -2000.00, 3, '$t3')
  , ({$app['test.reggie_id']}, '9000000000000000012', '$t3', 'return',          -1789.01, -1789.01, 3, '$t3')
  , ({$app['test.reggie_id']}, '9000000000000000013', '$t0', 'script-callback',  6789.01,  6789.01, 1, '$t1') -- partially returned, in multiple parts, one date unknown
  , ({$app['test.reggie_id']}, '9000000000000000013', '$t1', 'return',          -2000.00, -2000.00, 3, '$t3')
  , ({$app['test.reggie_id']}, '9000000000000000013', '$t3', 'return',          -1789.01, -1789.01, 1, '$t3')
  , ({$app['test.reggie_id']}, '9000000000000000014', '$t0', 'script-callback',   500.00,   500.00, 1, '$t1') -- returning 4/5 copies of a an item, in multiple parts
  , ({$app['test.reggie_id']}, '9000000000000000014', '$t1', 'return',           -100.00,  -100.00, 1, '$t2')
  , ({$app['test.reggie_id']}, '9000000000000000014', '$t2', 'return',           -200.00,  -200.00, 1, '$t3')
  , ({$app['test.reggie_id']}, '9000000000000000014', '$t3', 'return',           -100.00,  -100.00, 1, '$t3')",
  "INSERT INTO sf_rep_transaction_detail (trx_id, trx_detail_id, trx_detail_apply_total, trx_detail_total, product_id, quantity, units) VALUES
    ('9000000000000000001', '',     0, 1234.56,   '400086894574', 1, 'each')
  , ('9000000000000000002', '',     0, 2345.67,   '400086894575', 1, 'each')
  , ('9000000000000000002', '$t2',  0, -2345.67,  '400086894575', 1, 'each')
  , ('9000000000000000003', '',     0, 456.78,    '400086894576', 1, 'each')
  , ('9000000000000000003', '',     0, 3000.00,   '400086894577', 1, 'each')
  , ('9000000000000000003', '$t2',  0, -3000.00,  '400086894577', 1, 'each')
  , ('9000000000000000004', '',     0, 567.89,    '400086894578', 1, 'each')
  , ('9000000000000000004', '',     0, 4000.00,   '400086894579', 1, 'each')
  , ('9000000000000000004', '$t2',  0, -567.89,   '400086894578', 1, 'each')
  , ('9000000000000000004', '$t2',  0, -4000.00,  '400086894579', 1, 'each')
  , ('9000000000000000005', '',     0, 1678.90,   '400086894588', 1, 'each')
  , ('9000000000000000005', '',     0, 4000.00,   '400086894589', 1, 'each')
  , ('9000000000000000005', '$t2',  0, -1678.90,  '400086894588', 1, 'each')
  , ('9000000000000000005', '$t1',  0, -4000.00,  '400086894589', 1, 'each')
  , ('9000000000000000006', '',     0, 2000.00,   '400086894580', 1, 'each')
  , ('9000000000000000006', '',     0, 3000.00,   '400086894581', 1, 'each')
  , ('9000000000000000006', '',     0, 1789.01,   '400086894582', 1, 'each')
  , ('9000000000000000006', '$t2',  0, -2000.00,  '400086894580', 1, 'each')
  , ('9000000000000000006', '$t1',  0, -1789.01,  '400086894582', 1, 'each')
  , ('9000000000000000007', '',     0, 1000.00,   '400086894583', 1, 'each')
  , ('9000000000000000007', '',     0, 6890.12,   '400086894584', 1, 'each')
  , ('9000000000000000007', '$t2',  0, -1000.00,  '400086894583', 1, 'each')
  , ('9000000000000000007', '$t2',  0, -6890.12,  '400086894584', 1, 'each')
  , ('9000000000000000008', '',     0, 4567.89,   '400086894585', 1, 'each')
  , ('9000000000000000008', '$t2',  0, -4567.89,  '400086894585', 1, 'each')
  , ('9000000000000000009', '',     0, 1000.00,   '400086894586', 1, 'each')
  , ('9000000000000000009', '',     0, 3000.00,   '400086894587', 1, 'each')
  , ('9000000000000000009', '',     0, 1678.90,   '400086894590', 1, 'each')
  , ('9000000000000000009', '$t1',  0, -1000.00,  '400086894586', 1, 'each')
  , ('9000000000000000009', '$t1',  0, -3000.00,  '400086894587', 1, 'each')
  , ('9000000000000000009', '$t2',  0, -1678.90,  '400086894590', 1, 'each')
  , ('9000000000000000010', '',     0, 3500.00,   '400086894591', 1, 'each')
  , ('9000000000000000010', '',     0, 1500.00,   '400086894592', 1, 'each')
  , ('9000000000000000010', '',     0, 678.90,    '400086894593', 1, 'each')
  , ('9000000000000000010', '$t1',  0, -3500.00,  '400086894591', 1, 'each')
  , ('9000000000000000010', '$t1',  0, -1500.00,  '400086894592', 1, 'each')
  , ('9000000000000000010', '$t2',  0, -678.90,   '400086894593', 1, 'each')
  , ('9000000000000000011', '',     0, 3000.00,   '400086894594', 1, 'each')
  , ('9000000000000000011', '',     0, 456.78,    '400086894595', 1, 'each')
  , ('9000000000000000011', '$t2',  0, -3000.00,  '400086894594', 1, 'each')
  , ('9000000000000000012', '',     0, 3000.00,   '400086894596', 1, 'each')
  , ('9000000000000000012', '',     0, 2000.00,   '400086894597', 1, 'each')
  , ('9000000000000000012', '',     0, 1789.01,   '400086894598', 1, 'each')
  , ('9000000000000000012', '$t3',  0, -2000.00,  '400086894597', 1, 'each')
  , ('9000000000000000012', '$t2',  0, -1789.01,  '400086894598', 1, 'each')
  , ('9000000000000000013', '',     0, 3000.00,   '400086894596', 1, 'each')
  , ('9000000000000000013', '',     0, 2000.00,   '400086894597', 1, 'each')
  , ('9000000000000000013', '',     0, 1789.01,   '400086894598', 1, 'each')
  , ('9000000000000000013', '$t3',  0, -2000.00,  '400086894597', 1, 'each')
  , ('9000000000000000013', '$t1',  0, -1789.01,  '400086894598', 1, 'each')
  , ('9000000000000000014', '',     0, 500.00,    '400086894599', 5, 'each')
  , ('9000000000000000014', '$t1',  0, -100.00,   '400086894599', 1, 'each')
  , ('9000000000000000014', '$t2',  0, -200.00,   '400086894599', 2, 'each')
  , ('9000000000000000014', '$t3',  0, -100.00,   '400086894599', 1, 'each')",
];

// if called from the command line, this prints sql to STDOUT
// that can be piped directly into the mysql client
if (php_sapi_name() == 'cli') {
  foreach ($queries as $q) {
    echo $q . ";\n";
  }
}

return $queries;
