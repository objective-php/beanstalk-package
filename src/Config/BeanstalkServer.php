<?php

namespace ObjectivePHP\Package\Beanstalk\Config;

use ObjectivePHP\Config\Directive\AbstractMultiComplexDirective;
use Pheanstalk\Pheanstalk;

/**
 * Class BeanstalkServer
 *
 * This class is a configuration directive for the usage of ObjectivePHP beanstalk-package
 *
 * @package Fei\Service\SecondPartyLogistics\Tool\Package\Beanstalk\Config
 */
class BeanstalkServer extends AbstractMultiComplexDirective
{
    const KEY = 'beanstalk';

    protected $key = self::KEY;

    /**
     * Pheanstalk host
     *
     * @config-attribute
     * @config-example-value 127.0.0.1
     *
     * @var string
     */
    protected $host;

    /**
     * Pheanstalk port
     *
     * @config-attribute
     * @config-example-value 11300
     *
     * @var int
     */
    protected $port = Pheanstalk::DEFAULT_PORT;

    /**
     * Pheanstalk connection timeout
     *
     * @config-attribute
     * @config-example-value 10
     *
     * @var int
     */
    protected $connectTimeout;

    /**
     * Pheanstalk connection persistency
     *
     * @config-attribute
     * @config-example-value true
     *
     * @var bool
     */
    protected $connectPersistent = false;

    /**
     * Pheanstalk tube
     *
     * @config-attribute
     * @config-example-value mails
     *
     * @var string
     */
    protected $tube;

    /**
     * Get Host
     *
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * Set Host
     *
     * @param string $host
     *
     * @return $this
     */
    public function setHost(string $host)
    {
        $this->host = $host;
        return $this;
    }

    /**
     * Get Port
     *
     * @return int
     */
    public function getPort(): int
    {
        return $this->port;
    }

    /**
     * Set Port
     *
     * @param int $port
     *
     * @return $this
     */
    public function setPort(int $port)
    {
        $this->port = $port;
        return $this;
    }

    /**
     * Get ConnectTimeout
     *
     * @return int
     */
    public function getConnectTimeout()
    {
        return $this->connectTimeout;
    }

    /**
     * Set ConnectTimeout
     *
     * @param int $connectTimeout
     *
     * @return $this
     */
    public function setConnectTimeout(int $connectTimeout)
    {
        $this->connectTimeout = $connectTimeout;
        return $this;
    }

    /**
     * Get ConnectPersistent
     *
     * @return bool
     */
    public function getConnectPersistent(): bool
    {
        return $this->connectPersistent;
    }

    /**
     * Set ConnectPersistent
     *
     * @param bool $connectPersistent
     *
     * @return $this
     */
    public function setConnectPersistent(bool $connectPersistent)
    {
        $this->connectPersistent = $connectPersistent;
        return $this;
    }

    /**
     * Get Tube
     *
     * @return string
     */
    public function getTube(): string
    {
        return $this->tube;
    }

    /**
     * Set Tube
     *
     * @param string $tube
     *
     * @return $this
     */
    public function setTube(string $tube)
    {
        $this->tube = $tube;
        return $this;
    }
}
