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
use ObjectivePHP\ServicesFactory\ServicesFactory;
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
        $this->registerServices(
            $event->getApplication()->getServicesFactory(),
            $event->getApplication()->getConfig()->get(BeanstalkServer::KEY)
        );
    }

    /**
     * Register Beanstalk services into services factory
     *
     * @param ServicesFactory $servicesFactory
     * @param array $beanstalkServerConfig
     */
    public function registerServices(ServicesFactory $servicesFactory, array $beanstalkServerConfig)
    {
        /**
         * @var string $key
         * @var BeanstalkServer $config
         */
        foreach ($beanstalkServerConfig as $key => $config) {
            $servicesFactory->registerService([
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
