<?php
/**
 * 微信Access token类
 * User: H2H
 * Date: 2018/10/25
 * Time: 16:12
 */

namespace app\common\controller\wechat;


use app\common\controller\Api;
use app\common\controller\Curl;
use think\Cache;

class AccessToken
{

    public $data;
    public $needEcho = true;
    public static $GET_TOKEN = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=__APPID__&secret=__SECRET__";

    public function getToken()
    {
        # 先尝试从缓存拿取
        $token = $this->getByCache();

        # 缓存没有则从微信接口获取
        if (!$token) {
            $token = $this->getByHttp();
        }

        if ($this->needEcho) {
            Api::returnJson('200', ['token' => $token], '获取成功');
        }
        return $token;
    }

    /**
     * 从缓存获取Token
     * @return bool|string
     */
    private function getByCache()
    {
        $cache = Cache::tag('token')->get($this->data['mism_appid'] . "_token");
        return $cache;
    }

    /**
     * 从微信接口获取Token
     * @return string
     */
    private function getByHttp()
    {
        if (empty($this->data['mism_appid'])) Api::returnJson('500', [], 'mism_appid为空');
        if (empty($this->data['mism_appsecret'])) Api::returnJson('500', [], 'mism_appsecret为空');

        $url = str_replace("__APPID__", $this->data['mism_appid'], self::$GET_TOKEN);
        $url = str_replace("__SECRET__", $this->data['mism_appsecret'], $url);

        $CURL = Curl::getInstance();
        $res  = $CURL->send($url);
        $res  = json_decode($res, true);

        $ticket = isset($res['access_token']) ? $res['access_token'] : false;
        if ($ticket) {
            $cacheName = $this->data['mism_appid'] . "_token";
            Cache::tag('token')->set($cacheName, $ticket, 7200);
            return $ticket;
        }
        return false;
    }
}