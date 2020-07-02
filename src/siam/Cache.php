<?php
/**
 * 缓存器
 * User: Siam
 * Date: 2020/7/2 0002
 * Time: 23:13
 */

namespace Siam;


use Siam\AbstractInterface\CacheInterface;
use Siam\Component\Di;
use Siam\Component\Singleton;
use Siam\Trace\CacheFile;

class Cache implements CacheInterface
{
    use Singleton;

    /**
     * @var CacheInterface
     */
    private $cacheDriver;

    function __construct(CacheInterface $cache = null)
    {
        if ($cache === null) {
            $cache = new CacheFile(Di::getInstance()->get('CONFIG_CACHE_DIR'));
        }
        $this->cacheDriver = $cache;
    }

    public function set($key, $value, $ex)
    {
        return $this->cacheDriver->set($key, $value, $ex);
    }

    public function get($key)
    {
        return $this->cacheDriver->get($key);
    }

    public function del($key)
    {
        return $this->cacheDriver->del($key);
    }

    public function has($key)
    {
        return $this->cacheDriver->has($key);
    }
}