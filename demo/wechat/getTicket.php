<?php
/**
 * Created by PhpStorm.
 * User: Siam
 * Date: 2019/3/11
 * Time: 9:23
 */
require "../../vendor/autoload.php";
# 先获取token，接着获取Ticket
use \Siam\Wechat\AccessToken;
use \Siam\Wechat\ApiTicket;

$AccessToken = AccessToken::getInstance();
$AccessToken->setAppid('wxa50xx5919');
$AccessToken->setAppsecret('524ee05efdb2ef7xxx9b7ed189');
$AccessToken->setEcho(false); // 不设置则获取到echo就输出json 并且后续不会再执行

$token = $AccessToken->getToken();

$ApiTicket = ApiTicket::getInstance();
$ApiTicket->setAppid('wxa50xx5919');
$ApiTicket->setToken($token);
$ApiTicket->setEcho(false);

$ticket = $ApiTicket->getTicket();

echo $ticket;