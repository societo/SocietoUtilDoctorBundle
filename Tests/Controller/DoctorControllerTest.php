<?php

/**
 * This file is applied CC0 <http://creativecommons.org/publicdomain/zero/1.0/>
 */

namespace Societo\Util\DoctorBundle\Tests\Controller;

use Societo\BaseBundle\Test\WebTestCase;

class DoctorControllerTest extends WebTestCase
{
    public function testSuccess()
    {
        $client = static::createClient(array('root_config' => __DIR__.'/../config/config.php'));

        $client->request('GET', '/example');
        $this->assertEquals('valid', $client->getResponse()->getContent());

        $this->enableDoctor();

        $client->request('GET', '/example');
        $this->assertEquals('valid', $client->getResponse()->getContent());
    }

    public function testNotFound()
    {
        $client = static::createClient(array('root_config' => __DIR__.'/../config/config.php'));

        $crawler = $client->request('GET', '/unknown');
        $this->assertEquals(0, $crawler->filter('h1:contains("A medical certificate of this site")')->count());

        $this->enableDoctor();

        $crawler = $client->request('GET', '/unknown');
        $this->assertEquals(1, $crawler->filter('h1:contains("A medical certificate of this site")')->count());
        $this->assertEquals(1, $crawler->filter('h2:contains("Your specified page is not found, your action is denied or you cannot access this resource for some reason")')->count());
    }

    public function testNoDb()
    {
        $client = static::createClient(array('root_config' => __DIR__.'/../config/config.php'));

        $crawler = $client->request('GET', '/example?error=no_db');
        $this->assertEquals(0, $crawler->filter('h1:contains("A medical certificate of this site")')->count());

        $this->enableDoctor();

        $crawler = $client->request('GET', '/example?error=no_db');
        $this->assertEquals(1, $crawler->filter('h1:contains("A medical certificate of this site")')->count());
        $this->assertEquals(1, $crawler->filter('h2:contains("You don\'t create database yet")')->count());
    }

    public function testDbError()
    {
        $client = static::createClient(array('root_config' => __DIR__.'/../config/config.php'));

        $crawler = $client->request('GET', '/example?error=db_error');
        $this->assertEquals(0, $crawler->filter('h1:contains("A medical certificate of this site")')->count());

        $this->enableDoctor();

        $crawler = $client->request('GET', '/example?error=db_error');
        $this->assertEquals(1, $crawler->filter('h1:contains("A medical certificate of this site")')->count());
        $this->assertEquals(1, $crawler->filter('h2:contains("Your database or the programs for accessing to a database causes some errors")')->count());
    }

    public function testUnknownError()
    {
        $client = static::createClient(array('root_config' => __DIR__.'/../config/config.php'));

        $crawler = $client->request('GET', '/example?error=unknown_error');
        $this->assertEquals(0, $crawler->filter('h1:contains("A medical certificate of this site")')->count());

        $this->enableDoctor();

        $crawler = $client->request('GET', '/example?error=unknown_error');
        $this->assertEquals(1, $crawler->filter('h1:contains("A medical certificate of this site")')->count());
        $this->assertEquals(1, $crawler->filter('h2:contains("This might be a bug of Societo or plugins.")')->count());
    }

    public function enableDoctor()
    {
        static::$kernel->enableDoctor();
    }
}
