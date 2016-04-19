<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase {

    public function testIndex() {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
//        $this->assertContains('Welcome to Symfony', $crawler->filter('#container h1')->text());


        $form = $crawler->selectButton('Login')->form();
        $form['_password'] = 'admin';
        $form['_username'] = 'admin';

        $crawler = $client->submit($form);

        //Redirected to lobby
        $this->assertTrue($client->getResponse()->isRedirect());
        $client->followRedirect();

        $this->assertEquals(
                $client->getRequest()->get('_route'), 'lobby'
        );
    }

}
