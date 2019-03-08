<?php
/**
 * Created by PhpStorm.
 * User: Siam
 * Date: 2019/3/8
 * Time: 16:05
 */

require "../vendor/autoload.php";

\Siam\Api::json('200', [
    'testList' => [
        [1, "20"],
        [2, "35"]
    ],
    "userInfo" => ['Siam']
], "请求成功");

\Siam\Api::debug('test', "这是一个虚拟值");


# 定义field，便于在线上测试安卓接口
\Siam\Api::debug('test-field', "只有传参teee 不管是get还是post 才会打印这个值", "teee");