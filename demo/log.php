<?php
/**
 * Created by PhpStorm.
 * User: Siam
 * Date: 2019/3/8
 * Time: 15:48
 */


use Siam\Component\Di;

require "../vendor/autoload.php";

$log = \Siam\Logger::getInstance();

$res = $log->log("测试log");
var_dump($res);

# 级别
$res = $log->log("测试log", 'notice');
var_dump($res);

# 由于采用单例模式，以上的日志处理器行为已经决定，所以要测试日志处理器的更改，应该new一个新的对象，在应用中，应该在最开始决定日志处理器的运行方式，所以还是用单例模式

# 测试log路径
Di::getInstance()->set('CONFIG_LOG_DIR', "./log/");
$lognew = new \Siam\Logger();
$res = $lognew->log("测试log", 'die');
var_dump($res);

# 自定义日志处理器
$log3 = new \Siam\Logger(new \Siam\Trace\LoggerRedis());
$log3->log('test');