<?php
/**
 * Created by PhpStorm.
 * User: Siam
 * Date: 2019/5/5
 * Time: 16:31
 */

require  "../vendor/autoload.php";

$xml = \Siam\Xml::getInstance();

$str = <<<xml
<?xml version="1.0" encoding="UTF-8"?>
<ROOT>
  <HEAD>
    <App>COUNTER-CLIENT</App>
    <Ver>1000</Ver>
    <MsgID>188152992101</MsgID>
    <MsgRef>22b32e8e8192d377</MsgRef>
    <MsgCode>202006</MsgCode>
    <WorkDate>20180601</WorkDate>
    <WorkTime>145315</WorkTime>
    <OperID>oCiqUjvDShfxfEXDvhMefcHQgvek</OperID>
    <RspCode>0000</RspCode>
    <RspMsg>出票成功</RspMsg>
  </HEAD>
  <BODY>
    <RSP2006>
      <PrintResult>出票成功</PrintResult>
      <TotalCount>3</TotalCount>
      <PrintTicketInfo></PrintTicketInfo>
    </RSP2006>
  </BODY>
</ROOT>
xml;
$object = $xml->xmlToArray($str);


$tem =  $object->BODY->RSP2006->PrintTicketInfo[0]->TicketTypeDis->__toString();
var_dump($tem);
echo "\n";
var_dump($object);
