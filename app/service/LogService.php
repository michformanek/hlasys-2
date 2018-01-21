<?php

namespace App\Service;


use App\Model\Comment;
use App\Model\Log;
use App\Model\Proposal;
use App\Model\Status;
use App\Model\Vote;
use Kdyby\Doctrine\EntityManager;
use Nette\Security\User;

class LogService
{
    /**
     * @var User
     */
    private $user;

    private $entityManager;
    private $logRepository;


    /**
     * LogService constructor.
     * @param User $user
     * @param EntityManager $entityManager
     */
    public function __construct(
        User $user,
        EntityManager $entityManager
    )
    {
        $this->user = $user;
        $this->entityManager = $entityManager;
        $this->logRepository = $entityManager->getRepository(Log::class);
    }


    public function findByProposalId($id)
    {
        $proposalReference = $this->entityManager->getReference(Proposal::class, $id);
        return $this->logRepository->findBy(['proposal' => $proposalReference]);
    }

    public function log($proposal, $message)
    {
        $log = new Log();
        $log->setDate(new \DateTime());
        $log->setProposal($proposal);
        $log->setUser($this->entityManager->getReference(\App\Model\User::class, $this->user->getId()));
        $log->setText($message);
        $this->entityManager->persist($log);
        $this->entityManager->flush();
        return $log;
    }

    public function logProposalChanged(Proposal $proposal)
    {
        $message = " upravil návrh";
        return $this->log($proposal, $message);
    }

    public function logStatusChanged(Proposal $proposal, Status $originalStatus, Status $newStatus)
    {
        $message = " změnil stav návrhu z " . $originalStatus->getName() . " na " . $newStatus->getName();
        return $this->log($proposal, $message);
    }

    public function logProposalCreated(Proposal $proposal)
    {
        $message = " vytvořil hlasování";
        return $this->log($proposal, $message);
    }

    public function logProposalDeleted(Proposal $proposal)
    {
        $message = " přesunul návrh do koše";
        return $this->log($proposal, $message);
    }

    public function logCommentAdded($proposal, Comment $comment)
    {
        $message = " přidal komentář s id " . $comment->getId();
        return $this->log($proposal, $message);
    }

    public function logVoteAdded($proposal, Vote $vote)
    {
        $voteType = ($vote->getType()) ? "pro" : "proti";
        $message = " přidal hlas " . $voteType . " návrhu";
        return $this->log($proposal, $message);
    }

    public function logVoteChanged($proposal, Vote $vote)
    {
        $after = ($vote->getType()) ? "pro" : "proti";
        $before = ($vote->getType()) ? "proti" : "pro";
        $message = " změnil hlas z " . $before . " na " . $after;
        return $this->log($proposal, $message);
    }

    public function logWatchDeleted($proposal)
    {
        $message = " odstranil watch";
        return $this->log($proposal, $message);
    }

    public function logWatchAdded($proposal)
    {
        $message = " přidal watch";
        return $this->log($proposal, $message);
    }

    public function logCommentDeleted(Comment $comment)
    {
        $message = " odstranil komentář s id " . $comment->getId();
        return $this->log($comment->getProposal(), $message);

    }

    public function logCommentEdited(Comment $comment)
    {
        $message = " upravil komentář s id " . $comment->getId();
        return $this->log($comment->getProposal(), $message);
    }


}