<?php
$I = new ApiTester($scenario);

$I->wantTo("Start basic test");
$I->haveInDatabase('users', array('name' => 'test1'));
$I->haveInDatabase('users', array('name' => 'test2'));
$I->haveHttpHeader('Content-Type', 'application/json');
$I->sendGET("http://192.168.11.99/api/user", []);
$I->seeResponseCodeIs(200);
$I->seeResponseIsJson();
$I->seeResponseContainsJson(array('status' => 'ok'));
