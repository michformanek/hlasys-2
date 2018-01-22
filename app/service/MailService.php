<?php

namespace App\Service;

use App\Model\Comment;
use App\Model\Proposal;
use App\Model\Status;
use App\Model\Vote;
use Kdyby\Doctrine\EntityManager;
use Ublaboo\Mailing\MailFactory;

class MailService
{

    /**
     * @var EntityManager
     */
    private $entityManager;
    /**
     * @var MailFactory
     */
    private $mailFactory;

    /**
     * MailService constructor.
     * @param EntityManager $entityManager
     * @param MailFactory $mailFactory
     */
    public function __construct(
        EntityManager $entityManager,
        MailFactory $mailFactory
    )
    {
        $this->entityManager = $entityManager;
        $this->mailFactory = $mailFactory;
    }

    public function sendCommentAdded(Comment $comment)
    {
        $subject = "[HLASYS] Byl pridan novy komentar u navrhu: " . $comment->getProposal()->getTitle();
        $params = [
            'header' => "Nový komentář u návrhu",
            'subject' => $subject,
            'comment' => $comment,
            'recipients' => $this->getRecipients($comment->getProposal()),
            'url' => 'http://'.$_SERVER['SERVER_NAME'].'/proposal/detail/' . $comment->getProposal()->getId(),
        ];
        $mail = $this->mailFactory->createByType('App\Mailing\CommentMail', $params);
        $mail->send();
    }

    public function sendCommentDeleted(Comment $comment)
    {
        $subject = "[HLASYS] Byl odstranen komentar u navrhu: " . $comment->getProposal()->getTitle();
        $params = [
            'header' => "Smazaný komentář u návrhu",
            'subject' => $subject,
            'comment' => $comment,
            'recipients' => $this->getRecipients($comment->getProposal()),
            'url' => 'http://'.$_SERVER['SERVER_NAME'].'/proposal/detail/' . $comment->getProposal()->getId(),
        ];
        $mail = $this->mailFactory->createByType('App\Mailing\CommentMail', $params);
        $mail->send();
    }

    public function sendCommentEdited(Comment $comment)
    {
        $subject = "[HLASYS] Byl upraven komentar u navrhu: " . $comment->getProposal()->getTitle();
        $params = [
            'header' => "Upravený  komentář u návrhu",
            'title' => $subject,
            'comment' => $comment,
            'recipients' => $this->getRecipients($comment->getProposal()),
            'url' => 'http://'.$_SERVER['SERVER_NAME'].'/proposal/detail/' . $comment->getProposal()->getId(),
        ];
        $mail = $this->mailFactory->createByType('App\Mailing\CommentMail', $params);
        $mail->send();
    }

    public function sendVoteAdded(Vote $vote)
    {
        $subject = "[HLASYS] Byl přidán hlas u návrhu: " . $vote->getProposal()->getTitle();
        $params = [
            'header' => "Přidán hlas u návrhu",
            'title' => $subject,
            'vote' => $vote,
            'recipients' => $this->getRecipients($vote->getProposal()),
            'url' => 'http://'.$_SERVER['SERVER_NAME'].'/proposal/detail/' . $vote->getProposal()->getId(),
        ];
        $mail = $this->mailFactory->createByType('App\Mailing\VoteMail', $params);
        $mail->send();
    }

    public function sendVoteChanged(Vote $vote)
    {
        $subject = "[HLASYS] Byl změněn hlas u návrhu: " . $vote->getProposal()->getTitle();
        $params = [
            'header' => "Smazaný komentář u návrhu",
            'subject' => $subject,
            'vote' => $vote,
            'recipients' => $this->getRecipients($vote->getProposal()),
            'url' => 'http://'.$_SERVER['SERVER_NAME'].'/proposal/detail/' . $vote->getProposal()->getId(),
        ];
        $mail = $this->mailFactory->createByType('App\Mailing\VoteMail', $params);
        $mail->send();
    }

    public function sendStatusChanged(Proposal $proposal, Status $originalStatus, Status $newStatus)
    {
        $subject = "[HLASYS] Byl změněn stav u návrhu: " . $proposal->getTitle();
        $params = [
            'header' => "Změna stavu u návrhu",
            'subject' => $subject,
            'recipients' => $this->getRecipients($proposal),
            'text' => "Stav návrhu se změnil z " . $originalStatus->getName() . " na " . $newStatus->getName(),
            'url' => 'http://'.$_SERVER['SERVER_NAME'].'/proposal/detail/' . $proposal->getId(),
        ];
        $mail = $this->mailFactory->createByType('App\Mailing\StatusMail', $params);
        $mail->send();
    }

    public function sendProposalCreated(Proposal $proposal)
    {
        $subject = "[HLASYS] Byl přidán nový návrh: " . $proposal->getTitle();
        $params = [
            'title' => "Přidán nový návrh",
            'subject' => $subject,
            'items' => $proposal->getItems(),
            'recipients' => $this->getRecipients($proposal),
            'proposal' => $proposal,
            'url' => 'http://'.$_SERVER['SERVER_NAME'].'/proposal/detail/' . $proposal->getId(),
        ];
        $mail = $this->mailFactory->createByType('App\Mailing\ProposalMail', $params);
        $mail->send();
    }

    public function sendProposalEdited(Proposal $proposal)
    {
        $subject = "[HLASYS] Byl upraven návrh: " . $proposal->getTitle();
        $params = [
            'title' => "Návrh upraven",
            'subject' => $subject,
            'items' => $proposal->getItems(),
            'recipients' => $this->getRecipients($proposal),
            'proposal' => $proposal,
            'url' => 'http://'.$_SERVER['SERVER_NAME'].'/proposal/detail/' . $proposal->getId(),
        ];
        $mail = $this->mailFactory->createByType('App\Mailing\ProposalMail', $params);
        $mail->send();

    }

    public function sendProposalDeleted(Proposal $proposal)
    {
        $subject = "[HLASYS] Byl odstraněn návrh: " . $proposal->getTitle();
        $params = [
            'title' => "Návrh odstraněn",
            'subject' => $subject,
            'items' => $proposal->getItems(),
            'proposal' => $proposal,
            'recipients' => $this->getRecipients($proposal),
            'url' => 'http://'.$_SERVER['SERVER_NAME'].'/proposal/detail/' . $proposal->getId(),
        ];

        $mail = $this->mailFactory->createByType('App\Mailing\ProposalMail', $params);
        $mail->send();

    }


    //FIXME pouzit
    public function sendResult($proposal)
    {

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