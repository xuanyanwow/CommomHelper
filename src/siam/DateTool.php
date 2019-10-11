<?php

namespace Siam;


class DateTool
{

    /**
     * 两个日期跨越的区间
     * @param $start
     * @param $end
     * @return string|null
     */
    public static function dateSection($start, $end)
    {
        $diff = strtotime($start) - strtotime($end);

        $diffHour = bcdiv($diff, 60 * 60, 2); // 差距的小时
        $diffDay  = bcdiv($diffHour,24,2); // 差距的天数

        return abs($diffDay);
    }

    /* **********************

    取日期 测试案例

    $first = DateTool::getMonthFirst(time());
    $end   = DateTool::getMonthEnd($first);

    dump($first);
    halt($end);

    ********************** */

    /**
     * 获取时间当月的第一天日期 默认本月
     * @param $time
     * @param string $format
     * @return bool|false|string
     */
    public static function getMonthFirst($time = null, $format = 'Y-m-01')
    {
        if ($time === null){
            $time = time();
        }

        if (!is_numeric($time)){
            return false;
        }
        return date($format, $time);
    }

    /**
     * 获取月份最后一天的日期
     * @param $first
     * @param string $format
     * @return false|string
     */
    public static function getMonthEnd($first, $format = 'Y-m-d')
    {
        return date($format, strtotime("{$first} +1 month -1 day"));
    }
}