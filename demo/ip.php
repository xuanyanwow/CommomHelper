<?php
/**
 * Created by PhpStorm.
 * User: Siam
 * Date: 2019/3/8
 * Time: 16:03
 */

require "../vendor/autoload.php";

$ipClass = \Siam\Ip::getInstance();

echo $ipClass->getIp();
echo "\n<br/>";

# check
$res = $ipClass->checkIp(['127.0.0.3', '127.0.0.2'], $ipClass->getIp());

var_dump($res);