<?php

namespace App\Tests;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EmailServiceTest extends WebTestCase
{
    public function testEmailSentAfterFormSubmit()
    {
        $client = static::createClient();

        $client->enableProfiler();

        $browser = $client->request('GET','/');

        $form = $browser->selectButton('Submit')->form();
        $email = $form['email'] = 'mail@endemic.ru';
        $category = $form['category'] = 'nerdy';

        // submit that form
        $client->submit($form);

        $mailCollector = $client->getProfile()->getCollector('swiftmailer');

        // checks that an email was sent
        $this->assertSame(1, $mailCollector->getMessageCount());

        $collectedMessages = $mailCollector->getMessages();
        $message = $collectedMessages[0];

        // Asserting email data
        $this->assertInstanceOf('Swift_Message', $message);
        $this->assertSame(sprintf($_ENV['EMAIL_SUBJECT'],$category), $message->getSubject());
        $this->assertSame($email, key($message->getTo()));
    }
}
