<?php
/**
 * 微信支付通用类
 * User: H2H
 * Date: 2018/9/21
 * Time: 14:27
 */

namespace Siam\Wechat;

use Siam\Component\Singleton;

class Work
{
    use Singleton;

    private function __construct($config = [])
    {
        empty($config['key']) or $this->key = $config['key'];
    }

    private function __clone()
    {
        // TODO: Implement __clone() method.
    }

    /**
     * 数组转url参数格式
     * @param $array
     * @return string
     */
    public function arrayToUrl($array)
    {
        $buff = "";
        foreach ($array as $k => $v) {
            if ($k != "sign" && $v != "" && !is_array($v)) {
                $buff .= $k . "=" . $v . "&";
            }
        }

        $buff = trim($buff, "&");
        return $buff;
    }

    /**
     * 数组转url参数格式 (不过滤字段)
     * @param $array
     * @return string
     */
    public function arrayAllToUrl($array)
    {
        $buff = "";
        foreach ($array as $k => $v) {
            if (!is_array($v)) {
                $buff .= $k . "=" . $v . "&";
            }
        }

        $buff = trim($buff, "&");
        return $buff;
    }

    /**
     * 数组转xml
     * @param $array
     * @return string
     */
    public function arrayToXml($array)
    {
        $xml = "<xml>";
        foreach ($array as $key => $val) {
            if (is_numeric($val)) {
                $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
            } else {
                $xml .= "<" . $key . "><![CDATA[" . $val . "]]></" . $key . ">";
            }
        }
        $xml .= "</xml>";
        return $xml;
    }

    /**
     * xml转对象
     * @param $xml
     * @return \SimpleXMLElement
     */
    public function xmlToOjb($xml)
    {
        return simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
    }

    /**
     * 利用xml_parser解释器解析xml字符串
     * @param string $str
     * @return bool|mixed 成功返回数组，不成功返回false
     */
    public function xmlParser($str){

        $xml_parser = xml_parser_create();

        if(!xml_parse($xml_parser,$str,true)){

            xml_parser_free($xml_parser);

            return false;

        }else {

            return (json_decode(json_encode(simplexml_load_string($str,'SimpleXMLElement',LIBXML_NOCDATA)),true));

        }

    }
    /**
     * 对象转数组
     * @param $obj
     * @return bool|mixed
     */
    public function objToArray($obj){
        if (is_object($obj)){
            return json_decode(json_encode($obj, 256), true);
        }
        return false;
    }

    /**
     * 根据参数进行签名计算
     * @param $array
     * @param string $key
     * @return string
     */
    public function makeSign($array, $key = '')
    {
        if (empty($this->key) && empty($key)) return '请传入config[key]';

        $key = !empty($key) ? $key : $this->key;

        ksort($array);

        $signStr = $this->arrayToUrl($array);

        $signStr .= "&key=" . $key;

        return strtoupper(md5($signStr));
    }

    /**
     * @param null $config
     * @return mixed
     */
    public function getOpenid($config = null)
    {
        $APPID  = $config['appid'];
        $secret = $config['secret'];
        if (!isset($_GET['code'])) {
            if ($_SERVER['QUERY_STRING'] == '') {
                $REDIRECT_URI = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
            } else {
                $REDIRECT_URI = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING'];
            }
            $scope = 'snsapi_base';
            $url   = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . $APPID . '&redirect_uri=' . urlencode($REDIRECT_URI) . '&response_type=code&scope=' . $scope . '&state=wx' . '#wechat_redirect';
            header('Location:' . $url);
        } else {
            $code = $_GET["code"];
            $url  = "https://api.weixin.qq.com/sns/oauth2/access_token?appid={$APPID}&secret={$secret}&code={$code}&grant_type=authorization_code";
            $ch   = curl_init();
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($ch, CURLOPT_HEADER, FALSE);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            $res = curl_exec($ch);
            curl_close($ch);
            $openid = json_decode($res, true);
//            file_put_contents('log/getOpenid.txt', date('Y-m-d H:i:s')."\n".$res, FILE_APPEND);
            return $openid['openid'];
        }
    }

    /**
     * 退款解密
     * @param array $data
     * @return bool|string
     */
    public function decryption($data = array())
    {
        $encryption = base64_decode($data['req_info']);
        $key = md5($data['key']);
//        $str = @mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $encryption, MCRYPT_MODE_ECB);
//        $block = @mcrypt_get_block_size('rijndael_128', 'ecb');
//        $pad = ord($str[($len = strlen($str)) - 1]);
//        $len = strlen($str);
//        $pad = ord($str[$len - 1]);
//        return substr($str, 0, strlen($str) - $pad);

        $return = openssl_decrypt($encryption , 'AES-256-ECB', $key, OPENSSL_RAW_DATA);
        return $return;
    }

    /**
     * 根据货币检查单位
     * 调用set一般场景【接口(以分为单位)拿到的金额转换真实最低单位货币提交给微信】 刷卡100分=1元  转成日元 要除以100
     * 调用get一般场景【微信回调拿到的金额转换为分单位存到数据库】  微信回调1日元 = 100分 转成分单位 乘以100
     * @param $total_fee
     * @param $fee_type
     * @return string|int
     */
    public function checkFeeType($total_fee, $fee_type, $type = 'set')
    {
        switch ($fee_type){
            case 'JPY':
                if ($type == 'set'){
                    return ($total_fee / 100);
                }
                return ($total_fee * 100);
            default:
                return $total_fee;
        }
    }
}