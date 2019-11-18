<?php


namespace App\Service;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Swift_Mailer;
use Swift_Message;
use Exception;

class EmailService extends AbstractController {

    private $mailer;

    public function __construct(Swift_Mailer $mailer) {
        $this->mailer = $mailer;
    }

    public function sendEmail($template, $sender, $recipients, $subject, $data){

        try {
            $body =  $this->renderView(
                $template,
                $data
            );
        } catch (Exception $exception){
            throw new HttpException(400,$exception->getMessage());
        }

        $message = (new Swift_Message($subject))
            ->setFrom($sender)
            ->setTo($recipients)
            ->setBody(
                $body,
                'text/html'
            );

        $this->mailer->send($message);
    }
}