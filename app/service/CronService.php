<?php

namespace App\Service;


use Kdyby\Doctrine\EntityManager;
use Ublaboo\Mailing\MailFactory;

class CronService
{

    /**
     * @var EntityManager
     */
    private $entityManager;
    /**
     * @var MailFactory
     */
    private $mailFactory;

    public function __construct(
        EntityManager $entityManager,
        MailFactory $mailFactory
    )
    {
        $this->entityManager = $entityManager;
        $this->mailFactory = $mailFactory;
    }

    /**
     * @cronner-task Digest E-mail sending
     * @cronner-period 1 day
     * @cronner-time 23:30 - 05:00
     */
    public function sendDigestEmails()
    {
        $recipients = $this->entityManager->createQueryBuilder(); // uživatel, neodhlasované a nevypršené/nerozhodnuté návrhy
        foreach ($recipients as $recipient){
            $mail = $this->mailFactory->createByType('App\Mailing\DigestMail', $recipient['params']);
            $mail->send();
        }

    }
}