<?php

namespace App\Service;

use App\Model\Comment;
use App\Model\Proposal;
use App\Model\Status;
use App\Model\User;
use App\Model\Vote;
use Latte\Engine;
use Nette\Mail\IMailer;
use Nette\Mail\Message;
use Tracy\Debugger;
use Tracy\ILogger;

class MailService
{

    /**
     * @var IMailer
     */
    private $mailer;

    public function __construct(
        IMailer $mailer
    )
    {

        $this->mailer = $mailer;
    }

    private function sendCommentEmail(Comment $comment, $params)
    {
        $recipients = $this->getRecipients($comment->getProposal());
        if (count($recipients) < 1) return;
        $latte = new Engine();
        $mail = new Message();
        $this->addRecipients($mail, $recipients);
        $mail->setSubject($params['subject']);
        $mail->setHtmlBody($latte->renderToString(__DIR__ . '/templates/mail/comment.latte', $params));
        $this->sendMessage($mail);

    }

    private function sendVoteEmail(Vote $vote, $params)
    {
        $recipients = $this->getRecipients($vote->getProposal());
        if (count($recipients) < 1) return;
        $latte = new Engine();
        $mail = new Message();
        $this->addRecipients($mail, $recipients);
        $mail->setSubject($params['subject']);
        $mail->setHtmlBody($latte->renderToString(__DIR__ . '/templates/mail/vote.latte', $params));
        $this->sendMessage($mail);

    }

    private function sendStatusEmail(Proposal $proposal, $params)
    {
        $recipients = $this->getRecipients($proposal);
        if (count($recipients) < 1) return;
        $latte = new Engine();
        $mail = new Message();
        $this->addRecipients($mail, $recipients);
        $mail->setSubject($params['subject']);
        $mail->setHtmlBody($latte->renderToString(__DIR__ . '/templates/mail/status.latte', $params));
        $this->sendMessage($mail);

    }

    private function sendProposalEmail(Proposal $proposal, $params)
    {
        $recipients = $this->getRecipients($proposal);
        if (count($recipients) < 1) return;
        $latte = new Engine();
        $mail = new Message();
        $this->addRecipients($mail, $recipients);
        $mail->setSubject($params['subject']);
        $mail->setHtmlBody($latte->renderToString(__DIR__ . '/templates/mail/proposal.latte', $params));
        $this->sendMessage($mail);

    }

    public function sendCommentAdded(Comment $comment)
    {
        $subject = "[HLASYS] Byl pridan novy komentar u navrhu: " . $comment->getProposal()->getTitle();
        $params = [
            'header' => "Nový komentář u návrhu",
            'subject' => $subject,
            'comment' => $comment,
            'url' => 'http://'.$_SERVER['SERVER_NAME'].'/proposal/detail/' . $comment->getProposal()->getId(),
        ];
        $this->sendCommentEmail($comment, $params);
    }

    public function sendCommentDeleted(Comment $comment)
    {
        $subject = "[HLASYS] Byl odstranen komentar u navrhu: " . $comment->getProposal()->getTitle();

        $params = [
            'header' => "Smazaný komentář u návrhu",
            'subject' => $subject,
            'comment' => $comment,
            'url' => 'http://'.$_SERVER['SERVER_NAME'].'/proposal/detail/' . $comment->getProposal()->getId(),
        ];
        $this->sendCommentEmail($comment, $params);
    }

    public function sendCommentEdited(Comment $comment)
    {
        $subject = "[HLASYS] Byl upraven komentar u navrhu: " . $comment->getProposal()->getTitle();

        $params = [
            'header' => "Upravený  komentář u návrhu",
            'title' => $subject,
            'comment' => $comment,
            'url' => 'http://'.$_SERVER['SERVER_NAME'].'/proposal/detail/' . $comment->getProposal()->getId(),
        ];

        $this->sendCommentEmail($comment, $params);
    }

    public function sendVoteAdded(Vote $vote)
    {
        $subject = "[HLASYS] Byl přidán hlas u návrhu: " . $vote->getProposal()->getTitle();

        $params = [
            'header' => "Přidán hlas u návrhu",
            'title' => $subject,
            'vote' => $vote,
            'url' => 'http://'.$_SERVER['SERVER_NAME'].'/proposal/detail/' . $vote->getProposal()->getId(),
        ];

        $this->sendVoteEmail($vote, $params);
    }

    public function sendVoteChanged(Vote $vote)
    {
        $subject = "[HLASYS] Byl změněn hlas u návrhu: " . $vote->getProposal()->getTitle();

        $params = [
            'header' => "Smazaný komentář u návrhu",
            'subject' => $subject,
            'vote' => $vote,
            'url' => 'http://'.$_SERVER['SERVER_NAME'].'/proposal/detail/' . $vote->getProposal()->getId(),
        ];

        $this->sendVoteEmail($vote, $params);
    }


    public function sendStatusChanged(Proposal $proposal, Status $originalStatus, Status $newStatus)
    {
        $subject = "[HLASYS] Byl změněn stav u návrhu: " . $proposal->getTitle();

        $params = [
            'header' => "Změna stavu u návrhu",
            'subject' => $subject,
            'text' => "Stav návrhu se změnil z " . $originalStatus->getName() . " na " . $newStatus->getName(),
            'url' => 'http://'.$_SERVER['SERVER_NAME'].'/proposal/detail/' . $proposal->getId(),
        ];

        $this->sendStatusEmail($proposal, $params);
    }

    public function sendProposalCreated(Proposal $proposal)
    {
        $subject = "[HLASYS] Byl přidán nový návrh: " . $proposal->getTitle();

        $params = [
            'title' => "Přidán nový návrh",
            'subject' => $subject,
            'items' => $proposal->getItems(),
            'proposal' => $proposal,
            'url' => 'http://'.$_SERVER['SERVER_NAME'].'/proposal/detail/' . $proposal->getId(),
        ];

        $this->sendProposalEmail($proposal, $params);
    }

    public function sendProposalEdited(Proposal $proposal)
    {
        $subject = "[HLASYS] Byl upraven návrh: " . $proposal->getTitle();

        $params = [
            'title' => "Návrh upraven",
            'subject' => $subject,
            'items' => $proposal->getItems(),
            'proposal' => $proposal,
            'url' => 'http://'.$_SERVER['SERVER_NAME'].'/proposal/detail/' . $proposal->getId(),
        ];

        $this->sendProposalEmail($proposal, $params);

    }

    public function sendProposalDeleted(Proposal $proposal)
    {
        $subject = "[HLASYS] Byl odstraněn návrh: " . $proposal->getTitle();

        $params = [
            'title' => "Návrh odstraněn",
            'subject' => $subject,
            'items' => $proposal->getItems(),
            'proposal' => $proposal,
            'url' => 'http://'.$_SERVER['SERVER_NAME'].'/proposal/detail/' . $proposal->getId(),
        ];

        $this->sendProposalEmail($proposal, $params);

    }


    //FIXME pouzit
    public function sendResult($proposal)
    {

    }

    //FIXME pouzit
    public function sendDigest()
    {

    }

    public function sendResultEmail(Proposal $proposal, $recipients)
    {
        $latte = new Engine();
        $mail = new Message();
        $this->addRecipients($mail, $recipients);
        $mail->setSubject("[HLASYS] Bylo ukončeno hlasování u návrhu: "); //FIXME titulek + id
        $mail->setHtmlBody($latte->renderToString(__DIR__ . '/templates/mail/result.latte'));
        $this->sendMessage($mail);
    }

    public function sendMessage(Message $message)
    {
        $message->setFrom("HLASYS <mf21@seznam.cz>");

        try {
            $this->mailer->send($message);
        } catch (\Exception $exception) {
            Debugger::log($exception, ILogger::ERROR);
        }
    }

    private function addRecipients(Message $message, $recipients)
    {
        foreach ($recipients as $recipient) {
            $message->addBcc($recipient->getEmail());
        }

    }

    private function getRecipients($proposal)
    {
        $watches = $proposal->getWatches();
        $recipients = [];
        foreach ($watches as $watch) {
            $user = $watch->getUser();
            array_push($recipients, $user);
        }
        return $recipients;

    }

}