<?php

namespace App\Tests;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PageControllerTest extends WebTestCase
{

    public function testIndex(){

        $client = self::createClient();

        $client->request('GET','/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testIndexTitle(){

        $client = self::createClient();

        $client->request('GET','/');

        $this->assertSelectorTextContains('title', 'Receive jokes on email');
    }

    public function testIndexFormSubmit(){

        // remove file with jokes if exists
        unlink($_ENV['PATH_JOKE_STORE'].'/joke.txt');

        $client = self::createClient();

        $browser = $client->request('GET','/');

        $form = $browser->selectButton('Submit')->form();
        $form['email'] = 'mail@endemic.ru';
        $form['category'] = 'nerdy';

        // submit that form
        $client->submit($form);

        $this->assertSelectorTextContains('title', 'Receive jokes on email');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $this->assertFileExists($_ENV['PATH_JOKE_STORE'].'/joke.txt');


    }

    public function testIndexFormPostWrongRequest(){

        $client = self::createClient();

        // Wrong email
        $browser = $client->request('GET','/');

        $form = $browser->selectButton('Submit')->form();
        $form['email'] = 'mail_endemic.ru';
        $form['category'] = 'nerdy';

        // submit that form
        $client->submit($form);

        $this->assertEquals(400, $client->getResponse()->getStatusCode());

        // Wrong category
        $browser = $client->request('GET','/');

        $this->expectException(\InvalidArgumentException::class);
        $form = $browser->selectButton('Submit')->form();
        $form['email'] = 'mail@endemic.ru';
        $form['category'] = 'nerdy11';

        // submit that form
        $client->submit($form);
    }


}