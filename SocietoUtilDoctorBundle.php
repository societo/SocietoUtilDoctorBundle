<?php

/**
 * SocietoUtilDoctorBundle
 * Copyright (C) 2011 Kousuke Ebihara
 *
 * This program is under the EPL/GPL/LGPL triple license.
 * Please see the Resources/meta/LICENSE file that was distributed with this file.
 */

namespace Societo\Util\DoctorBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\Response;

use Societo\Util\DoctorBundle\Controller\DoctorController;

class SocietoUtilDoctorBundle extends Bundle
{
    public function boot()
    {
        $kernel = $this->container->get('kernel');
        if (!is_callable(array($kernel, 'isDoctor'))) {
            throw new \LogicException('You must define isDoctor() method to your kernel.');
        }

        $dispatcher = $this->container->get('event_dispatcher');
        $dispatcher->addListener(KernelEvents::EXCEPTION, function ($event) use ($kernel) {
            if ($kernel->isDoctor()) {
                $container = $kernel->getContainer();

                $controller = new DoctorController();
                $controller->setContainer($container);

                $event->setResponse($controller->doctorAction($event));
            }
        });
    }
}
