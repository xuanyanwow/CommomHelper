<?php
/**
 * Created by PhpStorm.
 * User: Siam
 * Date: 2019/3/8
 * Time: 16:00
 */
require "../vendor/autoload.php";

$curl = \Siam\Curl::getInstance();

try {
    $res = $curl->send("http://pay.xxx.com/payment/public/index.php/api/pay",['sign' => '123']);
    echo($res);
} catch (Exception $e) {
    # 在这里捕捉异常
    echo "error" . $e;die;
}