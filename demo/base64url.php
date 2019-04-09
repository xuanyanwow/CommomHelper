<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/9 0009
 * Time: 下午 11:52
 */

require "../vendor/autoload.php";

$str = "你好Siam";

$base64url = \Siam\Base64Url::encode($str);
echo $base64url ."\n";

echo \Siam\Base64Url::decode($base64url);


