<?php
/**
 * Created by PhpStorm.
 * User: Siam
 * Date: 2019/3/8
 * Time: 16:57
 */

require "../vendor/autoload.php";

\Siam\Component\Di::getInstance()->set('siam', 'test');

try {
    echo \Siam\Component\Di::getInstance()->get('siam');
} catch (Throwable $e) {
}

\Siam\Component\Di::getInstance()->set('siam2', '\Siam\Ip');
try {
    $obj = \Siam\Component\Di::getInstance()->get('siam2');
    var_dump($obj->getIp());
} catch (Throwable $e) {
}

