<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 2019/3/8
 * Time: 14:53
 */
namespace Siam\Component;

trait Singleton
{
    private static $instance;

    static function getInstance(...$args)
    {
        if(!isset(self::$instance)){
            self::$instance = new static(...$args);
        }
        return self::$instance;
    }
}