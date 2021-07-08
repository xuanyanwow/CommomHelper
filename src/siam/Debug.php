<?php
/**
 * 调试助手
 * User: Siam
 * Date: 2021/7/8
 * Time: 17:26
 */

namespace Siam;


class Debug
{
    public static function function_path($funcname)
    {
        try {
            if(is_array($funcname)) {
                $func = new ReflectionMethod($funcname[0], $funcname[1]);
                $funcname = $funcname[1];
            } else {
                $func = new ReflectionFunction($funcname);
            }
        } catch (ReflectionException $e) {
            echo $e->getMessage();
            return;
        }
        $start = $func->getStartLine() - 1;
        $end =  $func->getEndLine() - 1;
        $filename = $func->getFileName();
        echo "function $funcname defined by $filename($start - $end)\n";
    }

    public static function class_path()
    {
        $reflector = new ReflectionClass("Child");
        $fn = $reflector->getFileName();
        return $fn;
    }

    /**
     * 获取文件加载信息
     * @access public
     * @param  bool $detail 是否显示详细
     * @return integer|array
     */
    public static function get_included_files($detail = true)
    {
        $files = get_included_files();

        if ($detail) {
            $info = [];

            foreach ($files as $file) {
                $info[] = $file . ' ( ' . number_format(filesize($file) / 1024, 2) . ' KB )';
            }

            return $info;
        }

        return count($files);
    }

    /**
     * @var array 区间时间信息
     */
    protected static $info = [];

    /**
     * @var array 区间内存信息
     */
    protected static $mem = [];

    /**
     * 记录时间（微秒）和内存使用情况
     * @access public
     * @param  string $name  标记位置
     * @param  mixed  $value 标记值(留空则取当前 time 表示仅记录时间 否则同时记录时间和内存)
     * @return void
     */
    public static function remark($name, $value = '')
    {
        self::$info[$name] = is_float($value) ? $value : microtime(true);

        if ('time' != $value) {
            self::$mem['mem'][$name]  = is_float($value) ? $value : memory_get_usage();
            self::$mem['peak'][$name] = memory_get_peak_usage();
        }
    }

    /**
     * 统计某个区间的时间（微秒）使用情况 返回值以秒为单位
     * @access public
     * @param  string  $start 开始标签
     * @param  string  $end   结束标签
     * @param  integer $dec   小数位
     * @return string
     */
    public static function getRangeTime($start, $end, $dec = 6)
    {
        if (!isset(self::$info[$end])) {
            self::$info[$end] = microtime(true);
        }

        return number_format((self::$info[$end] - self::$info[$start]), $dec);
    }

    /**
     * 统计从开始到统计时的时间（微秒）使用情况 返回值以秒为单位
     * @access public
     * @param  integer $dec 小数位
     * @return string
     */
    public static function getUseTime($dec = 6)
    {
        return number_format((microtime(true) - THINK_START_TIME), $dec);
    }

    /**
     * 获取当前访问的吞吐率情况
     * @access public
     * @return string
     */
    public static function getThroughputRate()
    {
        return number_format(1 / self::getUseTime(), 2) . 'req/s';
    }

    /**
     * 记录区间的内存使用情况
     * @access public
     * @param  string  $start 开始标签
     * @param  string  $end   结束标签
     * @param  integer $dec   小数位
     * @return string
     */
    public static function getRangeMem($start, $end, $dec = 2)
    {
        if (!isset(self::$mem['mem'][$end])) {
            self::$mem['mem'][$end] = memory_get_usage();
        }

        $size = self::$mem['mem'][$end] - self::$mem['mem'][$start];
        $a    = ['B', 'KB', 'MB', 'GB', 'TB'];
        $pos  = 0;

        while ($size >= 1024) {
            $size /= 1024;
            $pos++;
        }

        return round($size, $dec) . " " . $a[$pos];
    }

    /**
     * 统计从开始到统计时的内存使用情况
     * @access public
     * @param  integer $dec 小数位
     * @return string
     */
    public static function getUseMem($dec = 2)
    {
        $size = memory_get_usage() - START_MEM;
        $a    = ['B', 'KB', 'MB', 'GB', 'TB'];
        $pos  = 0;

        while ($size >= 1024) {
            $size /= 1024;
            $pos++;
        }
        $dec = (int) $dec;

        return round($size, $dec) . " " . $a[$pos];
    }

    /**
     * 统计区间的内存峰值情况
     * @access public
     * @param  string  $start 开始标签
     * @param  string  $end   结束标签
     * @param  integer $dec   小数位
     * @return string
     */
    public static function getMemPeak($start, $end, $dec = 2)
    {
        if (!isset(self::$mem['peak'][$end])) {
            self::$mem['peak'][$end] = memory_get_peak_usage();
        }

        $size = self::$mem['peak'][$end] - self::$mem['peak'][$start];
        $a    = ['B', 'KB', 'MB', 'GB', 'TB'];
        $pos  = 0;

        while ($size >= 1024) {
            $size /= 1024;
            $pos++;
        }

        return round($size, $dec) . " " . $a[$pos];
    }
}