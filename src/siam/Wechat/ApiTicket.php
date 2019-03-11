<?php
/**
 * 微信JSSDK  apiticket类
 * User: H2H
 * Date: 2018/10/25
 * Time: 16:12
 */

namespace Siam\Wechat;

use Siam\Api;
use Siam\Component\Singleton;
use Siam\Curl;

class ApiTicket
{
    use Singleton;
    protected $data;
    protected $token;
    protected $needEcho = true;
    protected static $GET_TICKET = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=__TOKEN__&type=jsapi";

    /**
     * 设置appid
     * @param $appid
     */
    public function setAppid($appid){
        $this->data['mism_appid'] = $appid;
    }

    /**
     * 设置token
     * @param $token
     */
    public function setToken($token){
        $this->token = $token;
    }

    /**
     * 设置是否需要输出json 不是则返回
     * @param bool $need
     */
    public function setEcho($need = true){
        $this->needEcho = $need;
    }

    public function getTicket()
    {
        # 先尝试从缓存拿取
        $ticket = $this->getByCache();

        # 缓存没有则从微信接口获取
        if (!$ticket) {
            $ticket = $this->getByHttp();
        }
        if ($this->needEcho) {
            Api::json('200', ['data' => ['ticket' => $ticket]], '获取成功');
        }
        return $ticket;
    }

    /**
     * 从缓存获取Ticket
     * @return bool|string
     */
    private function getByCache()
    {
        if (empty($this->data['mism_appid'])) Api::json('500', [], 'mism_appid为空');
        // $cache = Cache::tag('ticket')->get($this->data['mism_appid'] . "_ticket");
        $cache = false;
        return $cache;
    }

    /**
     * 从微信接口获取Ticket
     * @return bool|string
     */
    private function getByHttp()
    {
        if (empty($this->token)) Api::json('500', [], 'token为空');

        $url = str_replace("__TOKEN__", $this->token, self::$GET_TICKET);

        $CURL = Curl::getInstance();
        try {
            $res = $CURL->send($url);
        } catch (\Exception $e) {
            Api::json('500', [], '请求ticket失败');
        }
        $res  = json_decode($res, true);

        $ticket = $res['ticket'];
        if ($ticket) {
            $cacheName = $this->data['mism_appid'] . "_ticket";
            // Cache::tag('ticket')->set($cacheName, $ticket, 7200);
            return $ticket;
        }
        return false;
    }
}