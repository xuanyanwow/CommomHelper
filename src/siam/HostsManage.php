<?php

namespace Siam;


class HostsManage {
    // hosts 文件路径
    protected $file;
    // hosts 记录数组
    protected $hosts = array();
    // 配置文件路径，默认为 __FILE__ . '.ini';
    protected $configFile;
    // 从 ini 配置文件读取出来的配置数组
    protected $config = array();
    // 配置文件里面需要配置的域名
    protected $domain = array();
    // 配置文件获取的 ip 数据
    protected $ip = array();
    public function __construct($file, $config_file = null) {
        $this->file = $file;
        if ($config_file) {
            $this->configFile = $config_file;
        } else {
            $this->configFile = __FILE__ . '.ini';
        }
        $this->initHosts()
            ->initCfg();
    }
    public function __destruct() {
        $this->write();
    }
    public function initHosts() {
        $lines = file($this->file);
        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line) || $line[0] == '#') {
                continue;
            }
            $item = preg_split('/\s+/', $line);
            $this->hosts[$item[1]] = $item[0];
        }
        return $this;
    }
    public function initCfg() {
        if (! file_exists($this->configFile)) {
            $this->config = array();
        } else {
            $this->config = (parse_ini_file($this->configFile, true));
        }
        $this->domain = array_keys($this->config['domain'] ?? []);
        $this->ip = $this->config['ip']?? "";
        return $this;
    }
    /**
     * 删除配置文件里域的 hosts
     */
    public function delAllGroup() {
        foreach ($this->domain as $domain) {
            $this->delRecord($domain);
        }
    }
    /**
     * 将域配置为指定 ip
     * @param string $env
     * @return static
     */
    public function addGroup($env) {
        if (! isset($this->ip[$env])) {
            return $this;
        }
        foreach ($this->domain as $domain) {
            $this->addRecord($domain, $this->ip[$env]);
        }
        return $this;
    }

    /**
     * 添加一条 host 记录
     * @param string $domain
     * @param string $ip
     * @return HostsManage
     */
    function addRecord($domain, $ip) {
        $this->hosts[$domain] = $ip;
        return $this;
    }

    /**
     * 删除一条 host 记录
     * @param string $domain
     * @return HostsManage
     */
    function delRecord($domain) {
        unset($this->hosts[$domain]);
        return $this;
    }
    /**
     * 写入 host 文件
     */
    public function write() {
        $str = '';
        foreach ($this->hosts as $domain => $ip) {
            $str .= $ip . "\t" . $domain . PHP_EOL;
        }
        file_put_contents($this->file, $str);
        return $this;
    }

    /**
     * @return array
     */
    public function getHosts(): array
    {
        return $this->hosts;
    }

    /**
     * @param array $hosts
     */
    public function setHosts(array $hosts)
    {
        $this->hosts = $hosts;
    }



}
