<?php
    /**
     * This file is part of the Objective PHP project
     *
     * More info about Objective PHP on www.objective-php.org
     *
     * @license http://opensource.org/licenses/GPL-3.0 GNU GPL License 3.0
     */
    
    namespace ObjectivePHP\Package\Beanstalk\Config;
    
    
    use ObjectivePHP\Config\Exception;
    use ObjectivePHP\Config\SingleValueDirectiveGroup;
    use ObjectivePHP\Primitives\Collection\Collection;

    class BeanstalkServer extends SingleValueDirectiveGroup
    {
        /**
         * BeanstalkServer constructor.
         *
         * @param $identifier
         * @param $value array Server configuration
         *
         * @throws Exception
         */
        public function __construct($identifier, $value)
        {
            // check value
            $value = Collection::cast($value)->toArray() + ['port' => null, 'timeout' => null, 'persistent' => false];

            $mandatory = ['host', 'tube'];

            $missing = array_diff($mandatory, array_keys($value));

            if($missing)
            {
                throw new Exception(sprintf('Missing %s keys in beanstalk configuration', implode(', ', $missing)));
            }

            parent::__construct($identifier, $value);
        }


    }
