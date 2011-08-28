<?php

/**
 * SocietoUtilDoctorBundle
 * Copyright (C) 2011 Kousuke Ebihara
 *
 * This program is under the EPL/GPL/LGPL triple license.
 * Please see the Resources/meta/LICENSE file that was distributed with this file.
 */

namespace Societo\Util\DoctorBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\FlattenException;

/**
 *
 *
 * @author Kousuke Ebihara <ebihara@php.net>
 */
class DoctorController extends Controller
{
    public function doctorAction($event)
    {
        list ($summary, $detail) = $this->diagnose($event);

        $env = array(
            'PHP'   => PHP_VERSION,
            'OS'    => PHP_OS,
            'SAPI'  => PHP_SAPI,
            'uname' => \php_uname(),
        );

        $ext = array();
        foreach (array_merge(get_loaded_extensions(), get_loaded_extensions(true)) as $v) {
            $ext[$v] = phpversion($v);
        }

        $exception = FlattenException::create($event->getException());

        return $this->render('SocietoUtilDoctorBundle:Doctor:doctor.html.twig', array(
            'base64_image' => base64_encode(file_get_contents(__DIR__.'/../doctor.png')),
            'summary' => $summary,
            'detail' => $detail,
            'exception' => $exception,
            'env' => $env,
            'ext' => $ext,
        ));
    }

    public function diagnose($event)
    {
        $exception = $event->getException();

        if ($exception instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
            return array(
                'Your specified page is not found, your action is denied or you cannot access this resource for some reason',
                '<p>Usually, this error is not a serious because this should be an expected error. General users will get a simple 404 Not Found error in this situation so do not worry about this.</p>'
                .'<p>You may see this error just after your installation of Societo. In this case, you must create "secure_default" page in the backend page. Societo does not have any frontend pages in default so you need to create new pages (Exceptionally, installer creates login page for keeping your path to backend).</p>',
            );
        }

        if ($exception instanceof \PDOException) {
            if (1049 == $exception->getCode()) {
                return array(
                    'You don\'t create database yet',
                    '<p>Please create your database first. Societo does not create this automatically.</p>',
                );
            }

            return array(
                'Your database or the programs for accessing to a database causes some errors',
                '<p>In this situation, you should read the following error details carefully. If the error is about databases or connections, please check your server environment.</p>',
            );
        }

        return array('', '');
    }
}
