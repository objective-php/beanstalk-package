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
    use ObjectivePHP\Config\Config;
    use ObjectivePHP\Package\Beanstalk\BeanstalkPackage;
    use ObjectivePHP\Package\Beanstalk\Config\BeanstalkServer;
    use ObjectivePHP\PHPUnit\TestCase;
    use ObjectivePHP\ServicesFactory\ServicesFactory;
    use ObjectivePHP\ServicesFactory\Specs\ClassServiceSpecs;

    class BeanstalkPackageTest extends TestCase
    {
        public function testServicesAreRegistered()
        {

            $config = new Config();
            $config->import(new BeanstalkServer('default', ['host' => '127.0.0.1', 'tube' => 'test']));
            $config->import(new BeanstalkServer('other', ['host' => '127.0.0.1', 'port' => 1234, 'tube' => 'test']));

            $servicesFactory = $this->createMock(ServicesFactory::class);
            $servicesFactory->expects($this->exactly(2))->method('registerService')
                            ->with($this->isInstanceOf(ClassServiceSpecs::class))
            ;
            $app = $this->createMock(ApplicationInterface::class);
            $app->method('getConfig')->willReturn($config);
            $app->method('getServicesFactory')->willReturn($servicesFactory);

            (new BeanstalkPackage())->registerServices($app);

        }
    }
