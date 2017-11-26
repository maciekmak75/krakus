<?php

namespace PanelBundle\Command;

class EmailSender
{

    private $transport;
    private $mailer;
    private $username;
    private $password;
    private $authmode;
    private $host;
    private $port;
    private $encryption;
    private $from;
    private $address;
    private $subject;
    private $body;
    private $message;
    private $bcc;

    public function __construct()
    {
        $this->username = 'powiadomienia@krakus.net';
        $this->password = 'Hagu3884';
        $this->authmode = 'login';
        $this->host = 's1.jchost01.pl';
        $this->port = 587;
        $this->encryption = 'tls';
        $this->from = array('powiadomienia@krakus.net' => 'ZPiT AGH Krakus');

        $this->transport = \Swift_SmtpTransport::newInstance($this->host, $this->port, $this->encryption)
            ->setUsername($this->username)
            ->setPassword($this->password)
            ->setAuthMode($this->authmode);
        $this->mailer = \Swift_Mailer::newInstance($this->transport);
    }

    public function CreateEmail($address, $subject, $body, $bcc = false)
    {
        if (is_array($address))
            $this->address = $address;
        else // bo jest to pojedyńczy mail i trzeba go opakować arajem
            $this->address = array($address);
        if ($bcc) {
            if (is_array($bcc))
                $this->bcc = $bcc;
            else // bo jest to pojedyńczy mail i trzeba go opakować arajem
                $this->bcc = array($bcc);
        }
        $this->subject = $subject;
        $this->body = $body;
    }

    public function SendEmail()
    {
        $result = false;
        $mssg = '<html><font face="Calibri"><body>' . $this->body . '<br><br>_________________________<br>Pozdrawiamy,<br>Zespół Pieśni i Tańca AGH Krakus.</body></font></html>';
        $this->message = \Swift_Message::newInstance()
            ->setSubject($this->subject)
            ->setFrom($this->from)
            ->setTo($this->address)
            ->setBcc($this->bcc)
            ->setBody($mssg, 'text/html');
        $result = $this->mailer->send($this->message);
        return $result;
    }

}
