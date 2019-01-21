<?php

namespace App\Service;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class Email extends AbstractController
{
    private $mailer;

    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function Send($receiver, $subject, $template = '', $attachment = '')
    {
        $transport = new \Swift_SmtpTransport('mail.setarehvanak.com',587);
        $transport->setUsername('site@setarehvanak.com');
        $transport->setPassword('1234567890');
        $mailer = new \Swift_Mailer($transport);

        if (!empty($attachment)){
            $message = (new \Swift_Message($subject))
                ->setFrom('site@setarehvanak.com')
                ->setTo($receiver)
                ->setBody($template, 'text/html')
                ->attach(new \Swift_Attachment($attachment,'attachment.pdf',  'application/pdf'))
            ;
        }else{
            $message = (new \Swift_Message($subject))
                ->setFrom('site@setarehvanak.com')
                ->setTo($receiver)
                ->setBody($template, 'text/html');
        }

        return $mailer->send($message);

    }

    public function ContactUs(){

    }

}
