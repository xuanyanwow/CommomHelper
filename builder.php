<?php
/**
 * 打包成phar使用
 * User: Siam
 * Date: 2021/7/8
 * Time: 17:15
 */

 unlink("./siam.phar");

//产生一个siam.phar文件
$phar = new Phar('siam.phar', 0, 'siam.phar');
// 添加src里面的所有文件到siam.phar归档文件
$phar->buildFromDirectory(dirname(__FILE__), "/^(?!(\.*idea))(.*)$/i");
//设置执行时的入口文件，第一个用于命令行，第二个用于浏览器访问，这里都设置为index.php
$phar->setDefaultStub('autoload.php', 'autoload.php');