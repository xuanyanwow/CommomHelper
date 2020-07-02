<?php
/**
 * 缓存处理器
 * User: Siam
 * Date: 2020/7/2 0002
 * Time: 23:07
 */

namespace Siam\AbstractInterface;


interface CacheInterface
{
    public function set($key, $value, $ex);
    public function get($key);
    public function del($key);
    public function has($key);
}