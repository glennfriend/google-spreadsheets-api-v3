<?php
use Nette\Mail\Message;
use Nette\Mail\SendmailMailer;

class MailHelper
{
    public static function sendStart()
    {
        $subject = '[auto] start - '. date("Y-m-d H:i:s");
        $body    = "";
        self::send($subject, $body);
    }

    public static function sendSuccess()
    {
        $subject = '[auto] success - '. date("Y-m-d H:i:s");
        $body    = "";
        self::send($subject, $body);
    }

    public static function sendFail()
    {
        $subject = '[auto] fail - '. date("Y-m-d H:i:s");
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
            ->addTo('your email')
            ->setSubject($subject)
            ->setBody($body);

        $mailer = new SendmailMailer;
        $mailer->send($mail);
    }

}

