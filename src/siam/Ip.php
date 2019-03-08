<?php
/**
 * Created by PhpStorm.
 * User: Siam
 * Date: 2019/3/8
 * Time: 16:02
 */

namespace Siam;


use Siam\Component\Singleton;

class Ip
{
    use Singleton;

    /**
     * 获取客户端ip
     * @param int $type
     * @param bool $client
     * @return string
     */
    public function getIp($type = 0,$client=true) {
        $type       =  $type ? 1 : 0;
        static $ip  =   NULL;
        if ($ip !== NULL) return $ip[$type];
        if($client){
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $arr    =   explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
                $pos    =   array_search('unknown',$arr);
                if(false !== $pos) unset($arr[$pos]);
                $ip     =   trim($arr[0]);
            }elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
                $ip     =   $_SERVER['HTTP_CLIENT_IP'];
            }elseif (isset($_SERVER['REMOTE_ADDR'])) {
                $ip     =   $_SERVER['REMOTE_ADDR'];
            }
        }elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip     =   $_SERVER['REMOTE_ADDR'];
        }
        // 防止IP伪造
        $long = sprintf("%u",ip2long($ip));
        $ip   = $long ? array($ip, $long) : array('0.0.0.0', 0);
        return $ip[$type];
    }


    /**
     * 验证访问ip
     * 根据客户端ip验证是否通过权限验证：
     * ① ip限制范围为空则通过 ② ip无*通配符并且客户端ip在范围内则通过 ③ ip有*通配符，则分段验证是否符合
     * @param array $ips
     * @param string $clientIp
     * @return bool
     */
    public function checkIp($ips = [], $clientIp = '')
    {
        # 判断是否限制了登陆ip
        if (empty($ips)){
            return true;
        }

        if ($clientIp == ''){
            $clientIp = $this->getIp();
        }

        if (in_array($clientIp, $ips)){
            return true;
        }

        // ip参数拆分成数组 循环对比每个段
        $check_ip_arr = explode('.', $clientIp);
        $bl           = false;

        foreach ($ips as $val) {
            if (strpos($val, '*') == false) {
                continue;
            }
            $arr = explode('.', $val);
            $bl  = true;
            for ($i = 0; $i < 4; $i++) {
                // 不等于* 就要进来检测，如果为*符号替代符就不检查
                if ($arr[$i] != '*') {
                    if ($arr[$i] != $check_ip_arr[$i]) {
                        $bl = false;
                        break; // 终止检查本个ip 继续检查下一个ip
                    }
                }
            }
            if ($bl) break; // 如果是true则终止匹配
        }

        if (!$bl) {
            return false;
        }

        return true;
    }

}