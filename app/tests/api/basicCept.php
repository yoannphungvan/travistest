<?php

require_once __DIR__ . '/ApiTester.php';

$I = new ApiTester($scenario);

$I->wantTo("test");
$I->haveHttpHeader('Content-Type', 'application/json');
$I->sendGet("http://hr.api.dev.salesfloor.net/looks/619", []);
$I->seeResponseCodeIs(200);
$I->seeResponseIsJson();

