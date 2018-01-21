<?php

namespace App\Service;


use App\Model\Proposal;
use App\Model\Vote;
use DateTime;
use App\Model\User;
use Kdyby\Doctrine\EntityManager;

class VoteService
{

    /**
     * @var User
     */
    private $user;
    /**
     * @var LogService
     */
    private $logService;
    /**
     * @var MailService
     */
    private $mailService;
    private $entityManager;
    private $voteRepository;

    /**
     * VoteService constructor.
     * @param LogService $logService
     * @param MailService $mailService
     * @param \Nette\Security\User $user
     * @param EntityManager $entityManager
     */
    public function __construct(
        LogService $logService,
        MailService $mailService,
        \Nette\Security\User $user,
        EntityManager $entityManager
    )
    {
        $this->voteRepository = $entityManager->getRepository(Vote::class);
        $this->entityManager = $entityManager;
        $this->user = $user;
        $this->logService = $logService;
        $this->mailService = $mailService;
    }

    public function vote($proposalId, $type)
    {
        $isNew = false;
        $proposalReference = $this->entityManager->getReference(Proposal::class, $proposalId);
        $userReference = $this->entityManager->getReference(User::class, $this->user->getId());
        $vote = $this->voteRepository->findOneBy(['proposal' => $proposalReference, 'user' => $userReference]);
        if ($vote == null) {
            $vote = new Vote();
            $isNew = true;
        }
        $vote->setUser($userReference);
        $vote->setDate(new DateTime());
        $vote->setProposal($proposalReference);
        $vote->setType($type);
        if ($vote->getId() == null) {
            $this->logService->logVoteAdded($proposalReference, $vote);
        } else {
            $this->logService->logVoteChanged($proposalReference, $vote);
        }
        $this->entityManager->persist($vote);
        $this->entityManager->flush();

        if ($isNew) {
            $this->mailService->sendVoteAdded($vote);
        } else {
            $this->mailService->sendVoteChanged($vote);
        }

        return $vote;
    }

    public function getVoteOfCurrentUser($proposalId)
    {
        $proposalReference = $this->entityManager->getReference(Proposal::class, $proposalId);
        $userReference = $this->entityManager->getReference(User::class, $this->user->getId());
        return $this->voteRepository->findOneBy(['proposal' => $proposalReference, 'user' => $userReference]);
    }

    public function findByProposalId($id)
    {
        $proposalReference = $this->entityManager->getReference(Proposal::class,$id);
        return $this->voteRepository->findBy(['proposal' => $proposalReference]);
    }
}