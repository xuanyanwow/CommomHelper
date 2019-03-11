<?php
/**
 * 微信JSSDK  apiticket类
 * User: H2H
 * Date: 2018/10/25
 * Time: 16:12
 */

namespace app\common\controller\wechat;


use app\common\controller\Api;
use app\common\controller\Curl;
use think\Cache;

class ApiTicket
{
    public $data;
    public $token;
    public $needEcho = true;
    public static $GET_TICKET = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=__TOKEN__&type=jsapi";

    public function getTicket()
    {
        # 先尝试从缓存拿取
        $ticket = $this->getByCache();

        # 缓存没有则从微信接口获取
        if (!$ticket) {
            $ticket = $this->getByHttp();
        }
        if ($this->needEcho) {
            Api::returnJson('200', ['ticket' => $ticket], '获取成功');
        }
        return $ticket;
    }

    /**
     * 从缓存获取Ticket
     * @return bool|string
     */
    private function getByCache()
    {
        if (empty($this->data['mism_appid'])) Api::returnJson('500', [], 'mism_appid为空');
        $cache = Cache::tag('ticket')->get($this->data['mism_appid'] . "_ticket");
        return $cache;
    }

    /**
     * 从微信接口获取Ticket
     * @return bool|string
     */
    private function getByHttp()
    {
        if (empty($this->token)) Api::returnJson('500', [], 'token为空');

        $url = str_replace("__TOKEN__", $this->token, self::$GET_TICKET);

        $CURL = Curl::getInstance();
        $res  = $CURL->send($url);
        $res  = json_decode($res, true);

        $ticket = $res['ticket'];
        if ($ticket) {
            $cacheName = $this->data['mism_appid'] . "_ticket";
            Cache::tag('ticket')->set($cacheName, $ticket, 7200);
            return $ticket;
        }
        return false;
    }
}