<?php
/**
 * This file is part of the Objective PHP project
 *
 * More info about Objective PHP on www.objective-php.org
 *
 * @license http://opensource.org/licenses/GPL-3.0 GNU GPL License 3.0
 */

namespace Tests\ObjectivePHP\Package\Beanstalk;

use ObjectivePHP\Application\ApplicationInterface;
use ObjectivePHP\Application\Workflow\WorkflowEvent;
use ObjectivePHP\Config\Config;
use ObjectivePHP\Package\Beanstalk\BeanstalkPackage;
use ObjectivePHP\Package\Beanstalk\Config\BeanstalkServer;
use ObjectivePHP\PHPUnit\TestCase;
use ObjectivePHP\ServicesFactory\ServicesFactory;
use Pheanstalk\Pheanstalk;

/**
 * Class BeanstalkPackageTest
 *
 * @package Tests\ObjectivePHP\Package\Beanstalk
 */
class BeanstalkPackageTest extends TestCase
{
    public function testGetConfig()
    {
        $this->assertEquals(
            (new BeanstalkPackage())->getConfig(),
            new Config(new BeanstalkServer())
        );
    }

    public function testOnPackagesInit()
    {
        $config = (new Config(new BeanstalkServer()))
            ->set(
                BeanstalkServer::KEY,
                [
                    "default" => [
                        "host" => "beanstalk",
                        "port" => "1234",
                        "connectTimeout" => 5,
                        "connectPersistent" => true,
                        "tube" => "test"
                    ],
                    "other" => [
                        "host" => "127.0.0.1",
                        "tube" => "test2"
                    ]
                ]
            );

        $servicesFactory = $this->createMock(ServicesFactory::class);
        $servicesFactory->expects($this->exactly(2))
            ->method('registerService')
            ->withConsecutive(
                [
                    [
                        'id'     => BeanstalkPackage::SERVICE_PREFIX . 'default',
                        'class'  => Pheanstalk::class,
                        'params' => [
                            "beanstalk",
                            "1234",
                            5,
                            true
                        ],
                        'setters' => [
                            'useTube' => "test"
                        ]
                    ]
                ],
                [
                    [
                        'id'     => BeanstalkPackage::SERVICE_PREFIX . 'other',
                        'class'  => Pheanstalk::class,
                        'params' => [
                            "127.0.0.1",
                            Pheanstalk::DEFAULT_PORT,
                            null,
                            false
                        ],
                        'setters' => [
                            'useTube' => "test2"
                        ]
                    ]
                ]
            );

        $app = $this->createMock(ApplicationInterface::class);
        $app->method('getConfig')->willReturn($config);
        $app->method('getServicesFactory')->willReturn($servicesFactory);

        (new BeanstalkPackage())
            ->onPackagesInit(new WorkflowEvent($app));
    }
}
