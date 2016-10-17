<?php

namespace TMG\Api\DocsBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiDocControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $this->assertEquals(
            301,
            $client->getResponse()->getStatusCode()
        );

        $this->assertTrue(
            $client->getResponse()->isRedirect('http://localhost/v2/docs/')
        );

        $crawler = $client->request('GET', '/v2/docs/');

        $this->assertEquals(
            200,
            $client->getResponse()->getStatusCode()
        );

        $this->assertContains(
            'Welcome! - TMG API',
            $client->getResponse()->getContent()
        );
    }
}
