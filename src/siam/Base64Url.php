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
        $base64 = str_replace("/", "_",$base64);
        $base64 = str_replace("+", "_a_",$base64);
        $base64 = str_replace("=", "_b_",$base64);
        return $base64;
    }

    /**
     * 解密
     * @param $str
     * @return mixed|null|string
     */
    static function decode($str)
    {
        if (!is_string($str)){
            return NULL;
        }
        $str = str_replace("_b_", "=",$str);
        $str = str_replace("_a_", "+",$str);
        $str = str_replace("_", "/",$str);
        $str = base64_decode($str);
        return $str;
    }
}