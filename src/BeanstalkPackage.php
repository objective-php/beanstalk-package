<?php
    /**
     * This file is part of the Objective PHP project
     *
     * More info about Objective PHP on www.objective-php.org
     *
     * @license http://opensource.org/licenses/GPL-3.0 GNU GPL License 3.0
     */
    
    namespace ObjectivePHP\Package\Beanstalk;
    
    
    use ObjectivePHP\Application\ApplicationInterface;
    use ObjectivePHP\Application\Middleware\AbstractMiddleware;
    use ObjectivePHP\Package\Beanstalk\Config\BeanstalkServer;
    use ObjectivePHP\ServicesFactory\Specs\ClassServiceSpecs;
    use ObjectivePHP\ServicesFactory\Specs\PrefabServiceSpecs;
    use Pheanstalk\Pheanstalk;

    /**
     * Class BeanstalkPackage
     *
     * @package ObjectivePHP\Package\Beanstalk
     */
    class BeanstalkPackage extends AbstractMiddleware
    {

        /**
         * Beanstalk services common prefix
         */
        const SERVICE_PREFIX = 'beanstalk.';


        /**
         * @var string
         */
        protected $bootstrapStep;


        /**
         * BeanstalkPackage constructor.
         *
         * @param string $bootstrapStep
         */
        public function __construct($bootstrapStep = 'bootstrap')
        {
            $this->bootstrapStep = $bootstrapStep;
        }
    
    
        /**
         * @param ApplicationInterface $app
         *
         * @throws Exception
         */
        public function run(ApplicationInterface $app)
        {
            if(empty($app->getSteps()[$this->bootstrapStep]))
            {
                throw new Exception(sprintf('Cannot plug Beanstalk services registration to specified application step: "%s" because this step has not been defined.', $this->bootstrapStep));
            }

            $app->getStep($this->bootstrapStep)->plug([$this, 'registerServices']);
        }

        /**
         * @param ApplicationInterface $app
         */
        public function registerServices(ApplicationInterface $app)
        {
            $servers = $app->getConfig()->subset(BeanstalkServer::class);
    
            foreach ($servers as $service => $data)
            {

                $serviceName = self::SERVICE_PREFIX . $service;

                $service = new ClassServiceSpecs($serviceName, Pheanstalk::class);
                $service->setParams([$data['host'], $data['port'], $data['timeout'], $data['persistent']]);
                $service->setSetters(['useTube' => [$data['tube']]]);

                $app->getServicesFactory()->registerService($service);
            }
        }
    
    }
