<?php

/**
 * This file is applied CC0 <http://creativecommons.org/publicdomain/zero/1.0/>
 */

use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\HttpFoundation\Response;

if (!class_exists('DoctorControllerTestController')) {
    class DoctorControllerTestController extends Symfony\Bundle\FrameworkBundle\Controller\Controller
    {
        public function action()
        {
            $error = $this->get('request')->query->get('error');
            if ('no_db' === $error) {
                throw new \PDOException('Something was wrong', 1049);
            } elseif ('db_error' === $error) {
                throw new \PDOException('Something was wrong' );
            } elseif ($error) {
                throw new \Exception('Something was wrong');
            }

            return new Response('valid');
        }
    }
}

$collection = new RouteCollection();
$collection->addCollection($loader->import($_SERVER['KERNEL_DIR'].'/config/routing.yml'));

$collection->add('example', new Route('/example', array(
    '_controller' => 'DoctorControllerTestController::action',
    'name' => 'example',
)));

return $collection;
