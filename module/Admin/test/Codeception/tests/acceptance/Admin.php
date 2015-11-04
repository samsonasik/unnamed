<?php

$I = new AcceptanceTester($scenario);
$I->wantTo('get admin page');
$I->amOnPage('/admin');
$I->see('Copyright 2015 - 2015 © Stanimir Dimitrov.');
