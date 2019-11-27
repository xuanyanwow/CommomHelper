<?php
/**
 * 任务模板基类 定义格式
 * User: Siam
 * Date: 2019/11/27
 * Time: 9:12
 */

namespace Siam\AbstractInterface;


abstract class CronAbstract
{
    public function __construct()
    {
        set_time_limit(0);
    }

    /**
     * 写明监控时间周期
     * @return mixed
     */
    abstract function rule();

    /**
     * 执行逻辑
     * @return mixed
     */
    public function run(){
        $data = $this->before();

        $res = $this->doJob($data);

        return $this->after($res);
    }

    /**
     * @return mixed
     */
    abstract function before();

    /**
     * @param null $data
     * @return bool
     */
    abstract function doJob($data = NULL);

    /**
     * @param bool $res
     * @return mixed
     */
    abstract function after($res = true);

    protected function log($string)
    {
        echo date('Y-m-d H:i:s')." {$string} ". PHP_EOL;
    }
}