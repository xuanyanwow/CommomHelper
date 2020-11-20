<?php
/**
 * 定时任务基础
 * User: Siam
 * Date: 2019/11/21
 * Time: 9:32
 */

namespace Siam\AbstractInterface;

abstract class CronBase
{
    protected static $singleCron = false;
    protected static $runtimePath;

    /**
     * CronBase constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        if(empty(static::$runtimePath)) static::$runtimePath = dirname(__FILE__);
        // 判断是否为单一任务 是的话停止运行
        $lockName = static::$runtimePath.DIRECTORY_SEPARATOR.$this->rule().".txt";
        if ( static::$singleCron && file_exists($lockName) ){
            throw new \Exception($this->rule() . " is single cron job");
        }
        if (static::$singleCron){
            file_put_contents($lockName, time());
        }
    }

    /**
     * 写明监控任务名
     * @return mixed
     */
    abstract function rule();

    /**
     * 执行逻辑
     * @return void
     * @throws \Throwable
     */
    public function run(){
        try{
            $data = $this->before();
            $res = $this->do($data);
        }catch (\Throwable $throwable){
            $this->clearClock();
            throw $throwable;
        }

        $this->clearClock();
        $this->after($res);
    }

    /**
     * @return mixed
     */
    abstract function before();

    /**
     * @param null $data
     * @return bool
     */
    abstract function do($data = NULL);

    /**
     * @param bool $res
     * @return mixed
     */
    abstract function after($res = true);

    private function clearClock()
    {
        $lockName = static::$runtimePath.DIRECTORY_SEPARATOR.$this->rule().".txt";
        if (file_exists($lockName)){
            unlink($lockName);
        }
    }
}
