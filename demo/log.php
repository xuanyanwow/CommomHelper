<?php
/**
 * Created by PhpStorm.
 * User: Siam
 * Date: 2019/3/8
 * Time: 15:48
 */

require "../vendor/autoload.php";

$log = \Siam\Logger::getInstance();

$res = $log->log("测试log");
var_dump($res);

# 级别
$res = $log->log("测试log", 'notice');
var_dump($res);

# 测试log路径
$log->setFilePath("./log/");
$res = $log->log("测试log", 'die');
var_dump($res);