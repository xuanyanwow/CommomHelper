<?php
/**
 * 文件型日志处理器
 * User: Siam
 * Date: 2019/3/8
 * Time: 17:15
 */

namespace Siam\Trace;


use Siam\AbstractInterface\LoggerAbstractInterface;

class LoggerFile implements LoggerAbstractInterface
{
    protected $filePath = "./";

    public function __construct($filePath = '')
    {
        if (!empty($filePath)){
            $this->filePath = $filePath;
        }
        $this->checkPathDir();
    }

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
        $temPath = $this->filePath.date('Y_m_d_').$level.".log";
        return file_put_contents($temPath, date("Y-m-d H:i:s") . "\n" . $str . "\n", FILE_APPEND)  ? true : false;
    }
}