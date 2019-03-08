<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 2019/3/8
 * Time: 14:28
 */

namespace Siam;

class Api
{
    public static $needLog = false;

    /**
     * 通用返回json封装
     * @param string $code
     * @param array $data
     * @param string $msg
     */
    public static function json($code, $data = [], $msg = '')
    {
        if (!empty($data) && !is_array(end($data))){
            die("json error");
        }

        $return = [
            'code' => "$code",
            'data' => (object) $data,
            'msg'  => $msg,
        ];

        $json = json_encode($return, 256);

        if (self::$needLog){
            # 记录log 会把所有输出的json记录起来 方便对接安卓调试
            Logger::getInstance()->log($json, "echoJson");
        }
        echo $json;
        exit;
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