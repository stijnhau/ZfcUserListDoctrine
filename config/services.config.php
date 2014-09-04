<?php
use Zend\ServiceManager\ServiceLocatorInterface;

return array(
    'factories' => array(
        'zfcuser_user_mapper' => function (ServiceLocatorInterface $sm) {
            /** @var $config \ZfcUserlist\Options\ModuleOptions */
            $config = $sm->get('zfcuserlist_module_options');
            $mapperClass = $config->getUserMapper();
            if (stripos($mapperClass, 'doctrine') !== false) {
                $mapper = new $mapperClass(
                    $sm->get('zfcuser_doctrine_em'),
                    $sm->get('zfcuser_module_options')
                );
            } else {
                /** @var $zfcUserOptions \ZfcUser\Options\UserServiceOptionsInterface */
                $zfcUserOptions = $sm->get('zfcuser_module_options');

                /** @var $mapper \ZfcUserlistDoctrine\Mapper\UserZendDb */
                $mapper = new $mapperClass();
                $mapper->setDbAdapter($sm->get('zfcuser_zend_db_adapter'));
                $entityClass = $zfcUserOptions->getUserEntityClass();
                $mapper->setEntityPrototype(new $entityClass);
                $mapper->setHydrator($sm->get('zfcuser_user_hydrator'));
            }

            return $mapper;
        },
    ),
);