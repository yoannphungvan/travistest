<?php

require_once __DIR__ . '/SfApiTester.php';

$I = new SfApiTester($scenario);

$I->wantTo("Start User Activity");
$I->haveHttpHeader('Content-Type', 'application/json');
$I->sendPost(URL . "/user-activity/start", ["userId" => 1, "source" => 'onboarding']);
$I->seeResponseCodeIs(200);
$I->seeResponseIsJson();
$I->seeResponseContainsJson([
  "success" => true
]);

$I->wantToTest("Stop User Activity");
$I->haveHttpHeader('Content-Type', 'application/json');
$I->sendPut(URL . "/user-activity/stop", ["userId" => 1]);
$I->seeResponseCodeIs(200);
$I->seeResponseIsJson();
$I->seeResponseContainsJson([
  "success" => true
]);
