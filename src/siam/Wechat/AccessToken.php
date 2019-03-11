<?php
/**
 * 微信Access token类
 * User: H2H
 * Date: 2018/10/25
 * Time: 16:12
 */

namespace Siam\Wechat;


use Siam\Api;
use Siam\Component\Singleton;
use Siam\Curl;

class AccessToken
{
    use Singleton;
    protected $data;
    protected $needEcho = true;
    protected static $GET_TOKEN = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=__APPID__&secret=__SECRET__";

    /**
     * 设置appid
     * @param $appid
     */
    public function setAppid($appid){
        $this->data['mism_appid'] = $appid;
    }

    /**
     * appsecret
     * @param $appsecret
     */
    public function setAppsecret($appsecret){
        $this->data['mism_appsecret'] = $appsecret;
    }

    /**
     * 设置是否需要输出json 不是则返回
     * @param bool $need
     */
    public function setEcho($need = true){
        $this->needEcho = $need;
    }

    public function getToken()
    {
        # 先尝试从缓存拿取
        $token = $this->getByCache();

        # 缓存没有则从微信接口获取
        if (!$token) {
            $token = $this->getByHttp();
        }

        if ($this->needEcho) {
            Api::json('200', ['data' => ['token' => $token]], '获取成功');
        }
        return $token;
    }

    /**
     * 从缓存获取Token
     * @return bool|string
     */
    private function getByCache()
    {
        // $cache = Cache::tag('token')->get($this->data['mism_appid'] . "_token");
        $cache = false;
        return $cache;
    }

    /**
     * 从微信接口获取Token
     * @return string
     */
    private function getByHttp()
    {
        if (empty($this->data['mism_appid'])) Api::json('500', [], 'mism_appid为空');
        if (empty($this->data['mism_appsecret'])) Api::json('500', [], 'mism_appsecret为空');

        $url = str_replace("__APPID__", $this->data['mism_appid'], self::$GET_TOKEN);
        $url = str_replace("__SECRET__", $this->data['mism_appsecret'], $url);

        $CURL = Curl::getInstance();
        try {
            $res = $CURL->send($url);
        } catch (\Exception $e) {
            Api::json('500', [], '请求失败');
        }
        $resA  = json_decode($res, true);

        $ticket = isset($resA['access_token']) ? $resA['access_token'] : false;
        if ($ticket) {
            $cacheName = $this->data['mism_appid'] . "_token";
            // Cache::tag('token')->set($cacheName, $ticket, 7200);
            return $ticket;
        }else{
            # 没有正常获取到那肯定有问题，输出提供给调试
            echo $res;
            die;
        }
    }
}