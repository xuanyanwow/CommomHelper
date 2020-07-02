<?php
/**
 * 基于文件的缓存驱动
 * User: Siam
 * Date: 2020/7/2 0002
 * Time: 23:07
 */

namespace Siam\Trace;


use Siam\AbstractInterface\CacheInterface;

class CacheFile implements CacheInterface
{
    protected $path = "../cache/";
    public function __construct($path = null)
    {
        if ($path !== null) $this->path = $path;
    }

    public function set($key, $value, $ex = 0)
    {
        $data = [
            'key'   => $key,
            'value' => $value,
            'ex'    => time() + $ex,
        ];
        $filename = $this->path . $key .".cache";
        $cache = json_encode($data,256);
        $int = file_put_contents($filename, $cache);

        return $int === strlen($cache);
    }

    public function get($key)
    {
        $filename = $this->path . $key .".cache";
        if (!is_file($filename)) return null;
        $data = file_get_contents($filename);
        $data = json_decode($data, true);
        if ($data['ex'] !== 0 && time() >= $data['ex']){
            $this->del($key);
            return null;
        }

        return $data['value'];
    }

    public function del($key)
    {
        $filename = $this->path . $key .".cache";
        if (!is_file($filename)) return false;
        unlink($filename);
        return true;
    }

    public function has($key)
    {
        $filename = $this->path . $key .".cache";
        if (!is_file($filename)) return false;
        return true;
    }
}