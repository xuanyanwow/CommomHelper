<?php

namespace Siam;

class Api
{
    public static function send($code, $data = [], $msg = '')
    {
        $return = [
            'code' => "$code",
            'data' => (object) $data,
            'msg'  => $msg,
        ];

        return json_encode($return, 256);
    }
    /**
     * 输出调试
     * @param $name string 标签名
     * @param $data mixed 值
     * @param string $field string 调用字段
     */
    public static function debug($name, $data, $field = '')
    {
        # 如果设置了field 则默认需要post或者get该字段的参数才打印
        if ($field !== ''){
            if (empty($_REQUEST[$field])) {
                return;
            }
        }

        echo $name."-->";

        if (is_object($data) or is_array($data)){
            echo json_encode($data, 256);
        }else{
            echo $data;
        }
        echo "<br/>\r\n";
    }

}