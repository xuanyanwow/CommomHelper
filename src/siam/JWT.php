<?php
/**
 * Created by PhpStorm.
 * User: Siam
 * Date: 2019/4/10
 * Time: 9:21
 */

namespace Siam;


use Siam\Component\Singleton;

class JWT
{
    use Singleton;

    private $alg = "AES";
    private $iss = "Siam";
    private $exp = 7200; // 默认2个小时
    private $sub;
    private $nbf;
    private $with = [];
    private $jwt;
    private $dataStr;
    private $headStr;
    private $secret_key = 'siamkey';
    private $signStr;

    function setAlg($alg)
    {
        $this->alg = $alg;
        return $this;
    }

    function setIss($iss)
    {
        $this->iss = $iss;
        return $this;
    }

    function setExp($exp)
    {
        $this->exp = $exp;
        return $this;
    }

    function setSub($sub)
    {
        $this->sub = $sub;
        return $this;
    }

    function setNbf($nbf)
    {
        $this->nbf = $nbf;
        return $this;
    }

    function setWith($data)
    {
        $this->with = $data;
        return $this;
    }

    function make()
    {
        $this->makeHead();
        $this->makeData();
        $this->makeSign();
        return $this->jwt();
    }

    private function makeHead()
    {
        $this->headStr = Base64Url::encode(json_encode([
            'alg' => $this->alg,
            'typ' => 'JWT',
        ]));
    }

    private function makeData()
    {
        $time       = time();
        $tem['iss'] = $this->iss;
        if (!empty($this->exp)) $tem['exp'] = ($time + $this->exp);
        $tem['sub'] = $this->sub;
        $tem['iat'] = $time;
        $tem['nbf'] = !empty($this->nbf) ? $this->nbf : $time; // 在此之前不可用
        if (!empty($this->with) && is_array($this->with)) $tem = array_merge($tem, $this->with);
        $this->dataStr = Base64Url::encode(json_encode($tem));

    }

    private function makeSign()
    {
        // 选用签名
        switch ($this->alg) {
            case 'AES':
                $this->signStr = Base64Url::encode(openssl_encrypt($this->headStr.".".$this->dataStr, 'AES-128-ECB', $this->secret_key, 0));
                break;
            default:
                $this->signStr = Base64Url::encode(openssl_encrypt($this->headStr.".".$this->dataStr, 'AES-128-ECB', $this->secret_key, 0));
                break;
        }
    }

    private function clear()
    {
        $this->alg        = "AES";
        $this->exp        = 7200; // 默认2个小时
        $this->sub        = "";
        $this->nbf        = "";
        $this->with       = [];
        $this->jwt        = "";
        $this->dataStr    = "";
        $this->headStr    = "";
        $this->signStr    = "";
    }

    function setSecretKey($key)
    {
        $this->secret_key = $key;
        return $this;
    }

    private function jwt()
    {
        $str = $this->headStr.".".$this->dataStr.".".$this->signStr;

        $this->clear();

        return $str;
    }

    function decode($str)
    {
        if ($str == '') return "STR NULL";

        $temArr = explode('.', $str);

        if (empty($temArr) && !is_array($temArr)) return 'STR ERROR';
        if (count($temArr) != 3) return 'STR ERROR';

        $this->headStr = $temArr[0];
        // 解head 拿算法
        $head = json_decode(Base64Url::decode($this->headStr), TRUE);

        if (!empty($head['alg'])){
            $this->alg = $head['alg'];
        }else{
            $this->clear();
            return 'ALG ERROR';
        }

        $this->dataStr = $temArr[1];

        // 验证签名
        $this->makeSign();
        if ($temArr[2] !== $this->signStr) {
            $this->clear();
            return 'SIGN ERROR';
        }

        $data = json_decode(Base64Url::decode($this->dataStr), TRUE);
        $time = time();

        // 在此之前不可用
        if (!empty($data['nbf']) && ($data['nbf'] > $time)){
            $this->clear();
            return 'NOTBEFORE';
        }

        // 是否已经过期
        if (!empty($data['exp']) && ($data['exp'] < $time)){
            $this->clear();
            return 'EXP';
        }

        // 返回解析数据
        return $data;

    }
}