<?php

namespace ObjectivePHP\Package\Beanstalk;

use ObjectivePHP\Application\Package\PackageInterface;
use ObjectivePHP\Application\Workflow\PackagesInitListener;
use ObjectivePHP\Application\Workflow\WorkflowEventInterface;
use ObjectivePHP\Config\Config;
use ObjectivePHP\Config\ConfigAccessorsTrait;
use ObjectivePHP\Config\ConfigInterface;
use ObjectivePHP\Config\ConfigProviderInterface;
use ObjectivePHP\Package\Beanstalk\Config\BeanstalkServer;
use Pheanstalk\Pheanstalk;

/**
 * Class BeanstalkPackage
 *
 * @package Fei\Service\SecondPartyLogistics\Tool\Package\Beanstalk
 */
class BeanstalkPackage implements PackageInterface, ConfigProviderInterface, PackagesInitListener
{
    use ConfigAccessorsTrait;

    /**
     * Beanstalk services common prefix
     */
    const SERVICE_PREFIX = 'beanstalk.';

    /**
     * @return ConfigInterface
     */
    public function getConfig(): ConfigInterface
    {
        return new Config(new BeanstalkServer());
    }

    /**
     * @param WorkflowEventInterface $event
     */
    public function onPackagesInit(WorkflowEventInterface $event)
    {
        /**
         * @var string $key
         * @var BeanstalkServer $config
         */
        foreach ($event->getApplication()->getConfig()->get(BeanstalkServer::KEY) as $key => $config) {
            $event->getApplication()->getServicesFactory()->registerService([
                'id'     => self::SERVICE_PREFIX . $key,
                'class'  => Pheanstalk::class,
                'params' => [
                    $config->getHost(),
                    $config->getPort(),
                    $config->getConnectTimeout(),
                    $config->getConnectPersistent()
                ],
                'setters' => [
                    'useTube' => $config->getTube()
                ]
            ]);
        }
    }
}
