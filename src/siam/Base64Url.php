<?php
/**
 * 常规base64在urlget参数传输中会冲突，因此将冲突字符处理
 * User: Siam
 * Date: 2019/4/9 0009
 * Time: 下午 11:47
 */

namespace Siam;


class Base64Url
{
    /**
     * 加密
     * @param $data
     * @return mixed|null|string
     */
    static function encode($data)
    {
        if (!is_string($data)){
            return NULL;
        }
        $base64 = base64_encode($data);
        $base64 = str_replace($base64, "/", "_");
        $base64 = str_replace($base64, "+", "_a_");
        $base64 = str_replace($base64, "=", "_b_");
        return $base64;
    }

    /**
     * 解密
     * @param $data
     * @return mixed|null|string
     */
    static function decode($data)
    {
        if (!is_string($data)){
            return NULL;
        }
        $str = base64_encode($data);
        $str = str_replace($str, "_", "/");
        $str = str_replace($str, "_a_", "+");
        $str = str_replace($str, "_b_", "=");
        return $str;
    }
}