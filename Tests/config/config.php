<?php

/**
 * This file is applied CC0 <http://creativecommons.org/publicdomain/zero/1.0/>
 */

use Symfony\Component\DependencyInjection\Definition;

$this->import($_SERVER['KERNEL_DIR'].'/config/config_test.yml');

$container->loadFromExtension('framework', array(
    'router' => array('resource' => __DIR__.'/routing.php'),
));
