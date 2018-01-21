<?php

namespace App\Service;


use App\Model\Log;
use App\Model\Proposal;
use App\Model\User;
use App\Model\Watch;
use App\Repository\WatchRepository;
use Kdyby\Doctrine\EntityManager;

class WatchService
{

    /**
     * @var \Nette\Security\User
     */
    private $user;
    /**
     * @var LogService
     */
    private $logService;
    private $entityManager;
    private $watchRepository;

    /**
     * WatchService constructor.
     * @param EntityManager $entityManager
     * @param LogService $logService
     * @param \Nette\Security\User $user
     */
    public function __construct(
        EntityManager $entityManager,
        LogService $logService,
        \Nette\Security\User $user
    )
    {
        $this->watchRepository = $entityManager->getRepository(Watch::class);
        $this->entityManager = $entityManager;
        $this->user = $user;
        $this->logService = $logService;
    }

    public function getWatchOfCurrentUser($proposalId)
    {
        $proposalReference = $this->entityManager->getReference(Proposal::class, $proposalId);
        $userReference = $this->entityManager->getReference(User::class, $this->user->getId());
        return $this->watchRepository->findOneBy(['proposal' => $proposalReference, 'user' => $userReference]);
    }

    public function addWatch($proposalId)
    {
        $proposalReference = $this->entityManager->getReference(Proposal::class, $proposalId);
        $userReference = $this->entityManager->getReference(User::class, $this->user->getId());
        $watch = new Watch();
        $watch->setProposal($proposalReference);
        $watch->setUser($userReference);
        $this->logService->logWatchAdded($proposalReference);
        $this->entityManager->persist($watch);
        $this->entityManager->flush();
    }

    public function removeWatch($watchId)
    {
        $watch = $this->entityManager->getReference(Watch::class, $watchId);
        $this->entityManager->remove($watch);
        $this->entityManager->flush();
    }

    public function findByProposalId($id)
    {
        $proposalReference = $this->entityManager->getReference(Proposal::class, $id);
        return $this->watchRepository->findBy(['proposal' => $proposalReference]);
    }

    public function deleteWatch($proposalId)
    {
        $proposal = $this->entityManager->getReference(Proposal::class, $proposalId);
        $watch = $this->getWatchOfCurrentUser($proposalId);
        $this->logService->logWatchDeleted($proposal);
        $this->entityManager->remove($watch);
        $this->entityManager->flush();
    }

}