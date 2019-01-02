<?php

namespace App\Tests;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;

class AvailabilityFunctionalTest extends WebTestCase
{

    /**
     * @dataProvider urlProvider
     */
    public function testPageIsSuccessful($url)
    {

        $client = self::createClient();
        $client->request('GET', $url);

        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    public function urlProvider()
    {
        yield ['/'];
        yield ['/user/login'];
        yield ['/admin/people'];
        yield ['/admin/people/permissions'];
        yield ['/admin/people/roles'];
    }

    protected function getClient(array $server = []): Client
    {
        $client = $this->getService('test.client');
        $client->setServerParameters($server);
        // Prevent loss of in-memory db between requests
        $client->disableReboot();

        return $client;
    }

}