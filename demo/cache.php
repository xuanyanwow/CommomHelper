<?php

require "../vendor/autoload.php";

use Siam\Cache;
var_dump(Cache::getInstance()->del('name'));// true
var_dump(Cache::getInstance()->has('name'));// false
$res = Cache::getInstance()->set('name', 'siam' , 1);
var_dump(Cache::getInstance()->get('name'));// siam
sleep(1);
var_dump(Cache::getInstance()->get('name'));// null
