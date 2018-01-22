<?php

namespace App\Mailing;

use Nette;
use Ublaboo\Mailing\IComposableMail;
use Ublaboo\Mailing\Mail;

class StatusMail extends Mail implements IComposableMail
{

    /**
     * @param  Nette\Mail\Message $message
     * @param  mixed $params
     * @return mixed
     */
    public function compose(Nette\Mail\Message $message, $params = null)
    {
        $message->setFrom($this->mails['default_sender']);
        $message->addTo($params['recipient']);
    }
}