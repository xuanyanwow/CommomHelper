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
    
    
    /**
     * 用于根据搜索条件构建缓存key
     */
    public static function buildCacheKey($pre, ...$data)
    {
        //     $cacheKey = buildCacheKey("test_", "123","456",["test"=>123],["test"=>["test1"=>123]]);
        //     var_dump($cacheKey);
        //     test_>>123>>456>>test-123>>test-{"test1":123}
        $cacheKey = $pre;
        foreach ($data as $item){
            $cacheKey .= ">>";
            if (!is_array($item)){
                $cacheKey .= $item;
            } else {
                foreach ($item as $itemKey => $itemValue){
                    if(is_array($itemValue)) $itemValue = json_encode($itemValue,256);
                    $cacheKey .= $itemKey."-".$itemValue;
                }
            }
        }

        return $cacheKey;
        
    }

}
