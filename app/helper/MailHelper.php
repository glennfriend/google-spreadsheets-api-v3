<?php
use Nette\Mail\Message;
use Nette\Mail\SendmailMailer;

class MailHelper
{

    public static function sendSuccess()
    {
        $subject = '[google-spreadsheets] success - '. date("Y-m-d H:i:s");
        $body    = "";
        self::send($subject, $body);
    }

    public static function sendFail()
    {
        $subject = '[google-spreadsheets] fail - '. date("Y-m-d H:i:s");
        $body    = "";
        self::send($subject, $body);
    }

    /* --------------------------------------------------------------------------------
        private
    -------------------------------------------------------------------------------- */

    private static function send($subject, $body)
    {
        $mail = new Message;
        $mail
            ->setFrom('google-spreadsheets-api-v3 <localhost@localhost.com>')
            // ->addTo('your email')
            // ->addTo('your email')
            // ->addTo('your email')
            ->setSubject($subject)
            ->setBody($body);

        $mailer = new SendmailMailer;
        $mailer->send($mail);
    }

}

