<?php
/**
 * 日志处理器接口
 * User: Siam
 * Date: 2019/3/8
 * Time: 17:14
 */

namespace Siam\AbstractInterface;


interface LoggerAbstractInterface
{
    public function log($str,  $level = 'debug');
}