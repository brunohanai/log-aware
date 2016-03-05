<?php

namespace brunohanai\LogAware\Action;

class MailAction implements IAction
{
    const OPTION_SUBJECT_KEY = 'subject';
    const OPTION_TO_KEY = 'to';
    const OPTION_FROM_KEY = 'from';
    const OPTION_HOST_KEY = 'host';
    const OPTION_PORT_KEY = 'port';
    const OPTION_USERNAME_KEY = 'username';
    const OPTION_PASSWORD_KEY = 'password';

    private $mailer;
    private $options;

    public function __construct(array $options = array())
    {
        $transport = \Swift_SmtpTransport::newInstance($options[self::OPTION_HOST_KEY], $options[self::OPTION_PORT_KEY])
            ->setUsername($options[self::OPTION_USERNAME_KEY])
            ->setPassword($options[self::OPTION_PASSWORD_KEY])
        ;

        $this->mailer = \Swift_Mailer::newInstance($transport);
        $this->options = $options;
    }

    public function doAction($content)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject($this->options[self::OPTION_SUBJECT_KEY])
            ->setBody($content)
            ->setTo($this->options[self::OPTION_TO_KEY])
            ->setFrom($this->options[self::OPTION_FROM_KEY])
        ;

        return $this->mailer->send($message);
    }
}