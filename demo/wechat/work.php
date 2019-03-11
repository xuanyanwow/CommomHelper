<?php
/**
 * Created by PhpStorm.
 * User: Siam
 * Date: 2019/3/11
 * Time: 9:42
 */
require "../../vendor/autoload.php";

use \Siam\Curl;
use \Siam\Wechat\Work;

$Work = Work::getInstance();

$MICROPAY_URL = "";

$config = [
    'mism_appid'   => '',
    'mism_mch_id'  => '',
    'sub_mch_id'   => '',
    'mism_mch_key' => '1',
];

$data     = array(
    'appid'            => $config['mism_appid'],
    'mch_id'           => $config['mism_mch_id'],
    'sub_mch_id'       => $config['sub_mch_id'],
    'spbill_create_ip' => '8.8.8.8',
    'nonce_str'        => md5(time()),
    'body'             => "wechat Pay", // 商品名称
    'out_trade_no'     => md5(time()),
    'total_fee'        => 100,
    'fee_type'         => "CNY",
    'auth_code'        => "11111"
);
# 排序
$sign           = $Work->makeSign($data, $config['mism_mch_key']);
$data['sign'] = $sign;

$xmlData = $Work->arrayToXml($data);

try {
    $res = Curl::getInstance()->send($MICROPAY_URL, $xmlData);
} catch (Exception $e) {
}

$res_    = $Work->xmlToOjb($res);

var_dump($res);