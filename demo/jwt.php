<?php
/**
 * Created by PhpStorm.
 * User: Siam
 * Date: 2019/4/10
 * Time: 9:59
 */
require "../vendor/autoload.php";

$jwt = \Siam\JWT::getInstance();

// echo $jwt->setIss('SiamTest')->setSecretKey('keykeykey')->setSub('Payment')->setWith(['username' => 'siam_name'])->make();



$data = \Siam\JWT::getInstance()->setSecretKey('keykeykey')->decode('eyJhbGciOiJBRVMiLCJ0eXAiOiJKV1QifQ_b__b_.eyJpc3MiOiJTaWFtVGVzdCIsImV4cCI6MTU1NDg2OTQ2OCwic3ViIjoiUGF5bWVudCIsImlhdCI6MTU1NDg2MjI2OCwibmJmIjoxNTU0ODYyMjY4LCJ1c2VybmFtZSI6InNpYW1fbmFtZSJ9.UHdYbFJHOGw5Qy8zMWpPVmtEdEpEb1FtbmpGK2Y0MVQrbDNDOU9ud0RzNWszVHZlVHdZcVBSbDVMZlVaWFd5ZnVIajIxSUgzR2crRTEvdzBzaDVBajY1N1lRN2FVQkFRVStnZXdGUVo3RDJKWlRxUko0ZkdLVEJUY1hlZ094TytQYzYwVll3QVVxUDlOYjFVMm5ueThEcC9jWEhBMjZKVExCa3REaTZrV0lROEY3YVRrbVF3ZnVKMWd6NlZzUC9keVFaa29DeE9QV2xhaVA5aVZERVhCR0NIdzRKU1cwUHo1VG9BUjA4ZkFPRk80ekFnZW5EenRNVHlQMHVheDl5cQ_b__b_');
var_dump($data);