<?php
/**
 * Created by PhpStorm.
 * User: Siam
 * Date: 2019/3/8
 * Time: 17:40
 */

namespace Siam\Trace;


use Siam\AbstractInterface\LoggerAbstractInterface;

class LoggerRedis implements LoggerAbstractInterface
{

    public function log($str, $level = 'debug')
    {
        // TODO: Implement log() method.
        echo "this is redis logger";
    }
}