<?php
/**
 * Xml
 * User: Siam
 * Date: 2019/4/18
 * Time: 11:19
 */

namespace Siam;


use Siam\Component\Singleton;

class Xml
{
    use Singleton;

    /**
     * 将数组转换为xml，可以多维
     * @param array $data
     * @param bool $root 是否添加头部
     * @return string
     */
    function arrayToXml($data, $root = true)
    {
        $str="";
        if($root)$str .= '<?xml version="1.0" encoding="UTF-8"?>';
        foreach($data as $key => $val){
            //去掉key中的下标[]
            $key = preg_replace('/\[\d*\]/', '', $key);
            if(is_array($val)){
                $child = $this->arrayToXml($val, false);
                $str .= "<$key>$child</$key>";
            }else{
                $str.= "<$key>$val</$key>";
            }
        }
        return $str;
    }


    function xmlToArray($xml)
    {
        // 清理替换没有闭合标签的
        $xml = preg_replace('/\<(\w+)\/\>/','<$1></$1>',$xml);

        $object = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
        $array  = json_decode(json_encode($object),true);
        $array  = $this->_checkType($array);
        return $array;
    }

    /**
     * 遍历检查数组格式，如果是空数组则转为字符串
     * @param $array
     * @return array
     */
    protected function _checkType($array){
        $tem = [];
        foreach ($array as $key => $value){
            if (is_array($value)){
                if (empty($value)){
                    $tem[$key] = "";
                }else{
                    $tem[$key] = $this->_checkType($value);
                }
            }else{
                $tem[$key] = $value;
            }
        }
        return $tem;
    }
}