<?php
/**
 * Created by PhpStorm.
 * User: Siam
 * Date: 2019/3/8
 * Time: 15:56
 */

namespace Siam;


use Siam\Component\Singleton;

class Curl
{
    use Singleton;

    private $head;
    public $responseHead;
    private $timeout = 60;


    /**
     * 设置Heard头
     * @Curl
     * @param $data
     */
    public function setHead($data)
    {
        $this->head = $data;
    }

    /**
     * 设置超时
     * @Curl
     * @param $s
     */
    public function setTimeout($s)
    {
        $this->timeout = $s;
    }

    /**
     * 发送curl请求
     * @param string $url 目标url
     * @param mixed $data 发送data
     * @param array $cert 证书文件绝对路径，一维数组，键名cert、key、ca
     * @param bool $post post还是get
     * @return mixed
     * @throws \Exception
     */
    public function send($url, $data = null, $cert = [], $post = true, $callable = null)
    {
        # 初始化一个cURL会话
        $curl = curl_init();

        // 设置请求选项, 包括具体的url
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);  // 禁用后cURL将终止从服务端进行验证
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_TIMEOUT, $this->timeout);          //单位 秒

        //设置curl默认访问为IPv4
        if(defined('CURLOPT_IPRESOLVE') && defined('CURL_IPRESOLVE_V4')){
            curl_setopt($curl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        }

        if ($post == true) {
            curl_setopt($curl, CURLOPT_POST, 1);  // 设置为post请求类型
        }

        if (!empty($cert)) {
            if (isset($cert['cert'])){
                curl_setopt($curl, CURLOPT_SSLCERTTYPE, 'PEM');
                curl_setopt($curl, CURLOPT_SSLCERT, $cert['cert']);
            }
            if (isset($cert['key'])){
                curl_setopt($curl, CURLOPT_SSLKEYTYPE, 'PEM');
                curl_setopt($curl, CURLOPT_SSLKEY, $cert['key']);
            }
            if (isset($cert['ca'])){
                curl_setopt($curl, CURLOPT_CAINFO, $cert['ca']);
            }
        }

        if (!empty($data)) {
            if (is_array($data)){
                $data = http_build_query($data);
            }
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);  // 设置具体的post数据
        }
        if (!empty($this->head)){
            curl_setopt($curl, CURLOPT_HEADER, 0);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $this->head);
            $this->setHead([]);
        }

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        // 执行自定义
        if (!empty($callable) && is_callable($callable)){
            call_user_func($callable, $curl);
        }
        $response = curl_exec($curl);  // 执行一个cURL会话并且获取相关回复

        if ($response === false) {
            $error = curl_error($curl);
            $errno = curl_errno($curl);
            curl_close($curl);  // 释放cURL句柄,关闭一个cURL会话
            throw new \Exception("[". $errno . "]" . $error);
        }

        $this->responseHead = curl_getinfo($curl);

        curl_close($curl);  // 释放cURL句柄,关闭一个cURL会话
        return $response;
    }

}