<?php
/**
 * Logger
 * User: Siam
 * Date: 2019/3/8
 * Time: 14:49
 */

namespace Siam;

use Siam\AbstractInterface\LoggerAbstractInterface;
use Siam\Component\Di;
use Siam\Component\Singleton;
use Siam\Trace\LoggerFile;

class Logger implements LoggerAbstractInterface
{
    use Singleton;

    /**
     * @var LoggerAbstractInterface
     */
    private $logger;

    function __construct(LoggerAbstractInterface $logger = null)
    {
        if ($logger === null) {
            $logger = new LoggerFile(Di::getInstance()->get('CONFIG_LOG_DIR'));
        }
        $this->logger = $logger;
    }

    /**
     * @param $str
     * @param string $level
     * @return bool
     */
    public function log($str, $level = 'debug')
    {
        // TODO: Implement log() method.
        return $this->logger->log($str, $level);
    }
}