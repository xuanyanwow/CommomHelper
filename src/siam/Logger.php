<?php
/**
 * Logger
 * User: Siam
 * Date: 2019/3/8
 * Time: 14:49
 */

namespace Siam;

use Siam\Component\Singleton;

class Logger
{

    protected $filePath = "./";
    use Singleton;

    /**
     * 设置log目录
     * @param $path
     */
    public function setFilePath($path)
    {
        $this->filePath = $path;
        $this->checkPathDir();
    }

    /**
     * 检查目录是否已经创建
     */
    private function checkPathDir()
    {
        if (!is_dir($this->filePath)) mkdir($this->filePath);
    }

    /**
     * 记录log
     * @param string $str
     * @param string $level
     * @return bool
     */
    public function log($str,  $level = 'debug')
    {
        $temPath = $this->filePath.$level.".log";
        return file_put_contents($temPath, date("Y-m-d H:i:s") . "\n" . $str . "\n", FILE_APPEND)  ? true : false;
    }



}