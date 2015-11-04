<?php

$I = new AcceptanceTester($scenario);
$I->wantTo('get application page');
$I->amOnPage('/');
$I->see('Copyright 2015 - 2015 © Stanimir Dimitrov.');
