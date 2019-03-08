<?php
/**
 * Created by PhpStorm.
 * User: Siam
 * Date: 2019/3/8
 * Time: 16:09
 */

require "../vendor/autoload.php";

$cryption = \Siam\Cryption::getInstance();

# 每次加密的结果都不一样 可以设置过期时间
$res = $cryption->encode("My name is siam", "siam");
echo $res;

echo "<br/>\n";

# 解码
$deRes = $cryption->decode("7c80mR4b0tRG4fNB86pwJfDNz8ZLNzTZqfLYTpiO2w6liPyY5qWN2vD4Udc", "siam");
echo $deRes;

