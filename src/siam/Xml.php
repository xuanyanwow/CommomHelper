<?php
/**
 * Xml
 * User: Siam
 * Date: 2019/4/18
 * Time: 11:19
 */

namespace Siam;


use Siam\Component\Singleton;
use SimpleXMLElement;

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

    public function toArray($simpleXMLElement, $list_field_setting = []): array
    {
        $return = [];
        /**
         * @var  $key
         * @var SimpleXMLElement $value
         */
        foreach ($simpleXMLElement as $key => $value){
            if ($value->count()){
                $temp = $this->toArray($value, $list_field_setting);
                if (in_array($key, $list_field_setting)){
                    $return[$key][] = $temp;
                }else{
                    $return[$key] = $temp;
                }
            }else{
                $return[$key] = $value->__toString();
            }
        }
        return $return;
    }

    public function xmlToArray($xml_string, $list_identify = null, $list_field_setting = []): array
    {
        $list = [];

        if (!$list_identify){
            $list = $this->toArray(simplexml_load_string($xml_string), $list_field_setting);
        }else{
            //  加载XML内容
            $xml_reader = new \XMLReader();
            $xml_reader->xml($xml_string);
            // move the pointer to the first product
            while ($xml_reader->read() && $xml_reader->name != $list_identify);

            // loop through the products
            while ($xml_reader->name == $list_identify)
            {
                // load the current xml element into simplexml and we’re off and running!
                $xml = simplexml_load_string($xml_reader->readOuterXML());

                // // 读取dom和值
                // var_dump($xml);
                //
                // // 读取dom的属性
                // foreach ($xml->city->attributes() as $key => $value){
                //     var_dump($key);
                //     var_dump($value->__toString());
                // }

                $list[] = $this->toArray($xml, $list_field_setting);

                $xml_reader->next($list_identify);
            }

            // don’t forget to close the file
            $xml_reader->close();
        }

        return $list;
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