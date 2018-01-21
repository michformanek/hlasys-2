<?php

namespace App\Service;


use App\Model\Group;
use App\Model\Item;
use App\Model\Proposal;
use App\Model\ProposalVoter;
use App\Model\Status;
use App\Model\User;
use App\Model\Watch;
use Kdyby\Doctrine\EntityManager;
use Nette\DateTime;

class ProposalService
{

    /**
     * @var \Nette\Security\User
     */
    private $user;
    /**
     * @var VoteTypeService
     */
    private $voteTypeService;
    /**
     * @var MailService
     */
    private $mailService;
    /**
     * @var LogService
     */
    private $logService;
    private $proposalVotersRepository;
    private $entityManager;
    private $proposalRepository;

    /**
     * ProposalService constructor.
     * @param EntityManager $entityManager
     * @param VoteTypeService $voteTypeService
     * @param MailService $mailService
     * @param LogService $logService
     * @param \Nette\Security\User $user
     */
    public function __construct(
        EntityManager $entityManager,
        VoteTypeService $voteTypeService,
        MailService $mailService,
        LogService $logService,
        \Nette\Security\User $user
    )
    {
        $this->proposalRepository = $entityManager->getRepository(Proposal::class);
        $this->entityManager = $entityManager;
        $this->user = $user;
        $this->voteTypeService = $voteTypeService;
        $this->mailService = $mailService;
        $this->logService = $logService;
        $this->proposalVotersRepository = $entityManager->getRepository(ProposalVoter::class);
    }

    public function findAll()
    {
        return $this->proposalRepository->findAll();
    }

    public function findOne($id)
    {
        return $this->proposalRepository->find($id);
    }

    public function findMine()
    {
        $userReference = $this->entityManager->getReference(User::class, $this->user->getId());
        return $this->proposalRepository->findBy(['user' => $userReference]);
    }

    public function findDeleted()
    {
        $userReference = $this->entityManager->getReference(User::class, $this->user->getId());
        return $this->proposalRepository->findBy(['user' => $userReference, 'trash' => true]);
    }

    public function getFormDefaults($proposal, $dateEnd, $dateStart)
    {
        if ($proposal->getId() == null) return [
            'dateStart' => $dateStart->format('d.m.Y'),
            'dateEnd' => $dateEnd->format('d.m.Y'),
        ];
        return [
            'description' => $proposal->getDescription(),
            'title' => $proposal->getTitle(),
            'id' => $proposal->getId(),
            'group' => $proposal->getGroup()->getId(),
            'dateStart' => $proposal->getDateStart()->format('d.m.Y'),
            'dateEnd' => $proposal->getDateStart()->format('d.m.Y'),
        ];
    }

    /*FIXME*/
    public function processForm($values)
    {
        $id = (int)$values['id'];
        $proposal = $this->proposalRepository->find($id);
        if ($proposal == null) {
            $proposal = new Proposal();
        }
        $proposal->setUser($this->entityManager->getReference(User::class, $this->user->getId()));
        $proposal->setDateEnd(new DateTime());
        $proposal->setDateStart(new DateTime()); //FIXME;
        $proposal->setGroup($this->entityManager->getReference(Group::class, $values['group']));
//        $proposal->setVoteType($this->entityManager->getReference(VoteType::class, $values['voteType']));
        $proposal->setStatus($this->entityManager->getReference(Status::class, 1));
        $proposal->setTitle($values['title']);
        $proposal->setTrash(false);
        $proposal->setDescription($values['description']);
        //    dump($proposal);
        //   exit();
        $this->entityManager->persist($proposal);
        $this->entityManager->flush();
        return $proposal;
    }

    public function save($proposal)
    {
        $this->entityManager->persist($proposal);
        $this->entityManager->flush();
        return $proposal;

    }

    public function deleteProposal($proposalId)
    {
        $proposal = $this->proposalRepository->find($proposalId);
        $proposal->setTrash(true);
        $this->logService->logProposalDeleted($proposal);
        $this->mailService->sendProposalDeleted($proposal);
        return $this->save($proposal);
    }

    public function updateProposal($values, $id)
    {
        $proposal = $this->proposalRepository->find($id);
        $status = $this->entityManager->getReference(Status::class, $values['status_id']);
        $voteType = $this->voteTypeService->find($values['vote_type_id']);

        $proposal->setVoteType($voteType);
        $this->changeStatus($proposal, $status);
        $proposal->setTitle($values['title']);
        $proposal->setDescription($values['description']);
        $proposal->setItems($this->createItems($values['items']));
        $this->logService->logProposalChanged($proposal);
        $this->mailService->sendProposalEdited($proposal);

        return $this->save($proposal);
    }

    /**
     * @param Proposal $proposal
     * @param Status $status
     */
    public function changeStatus(Proposal $proposal, Status $status)
    {
        $originalStatus = $proposal->getStatus();
        if ($originalStatus->getId() == $status->getId()) return;
        $proposal->setStatus($status);
        $this->logService->logStatusChanged($proposal, $originalStatus, $status);
        $this->mailService->sendStatusChanged($proposal, $originalStatus, $status);
        $this->save($proposal);
    }

    public function createProposal($values)
    {
        $voteType = $this->voteTypeService->find($values['vote_type_id']);
        $user = $this->entityManager->getReference(User::class, $this->user->getId());
        $status = $this->entityManager->getReference(Status::class, $values['status_id']);
        $dateStart = new \DateTime('now');
        $dateEnd = new \DateTime('now');
        $dateEnd->modify("+ 30 days");

        $proposal = new Proposal();

        $proposal->setTitle($values['title']);
        $proposal->setGroup($voteType->getGroup());
        $proposal->setUser($user);
        $proposal->setDateEnd($dateEnd);
        $proposal->setDateStart($dateStart);
        $proposal->setItems($this->createItems($values['items']));
        $proposal->setTrash($values['trash']);
        $proposal->setDescription($values['description']);
        $proposal->setStatus($status);
        $proposal->setVoteType($voteType);
        $proposal->setWatches($this->createWatchOfCurrentUser($proposal));
        $this->logService->logProposalCreated($proposal);
        $this->mailService->sendProposalCreated($proposal);
        return $this->save($proposal);
    }

    private function createItems($items)
    {
        $result = array();
        foreach ($items as $i) {
            $item = new Item();
            $item->setUrl($i['url']);
            $item->setPrice($i['url']);
            $item->setName($i['url']);
            $item->setCode($i['url']);
            $item->setAmount($i['url']);
            $result[] = $item;
        }
        return $result;
    }

    private function createWatchOfCurrentUser($proposal)
    {
        $watch = new Watch();
        $watch->setProposal($proposal);
        $user = $this->entityManager->getReference(User::class, $this->user->getId());
        $watch->setUser($user);
        return [$watch];
    }

    public function findUsingPaginator($itemsPerPage, $offset)
    {
        return $this->proposalRepository->findBy([],  ['dateStart' => 'DESC'], $itemsPerPage, $offset);
    }

    public function findMineUsingPaginator($itemsPerPage, $offset)
    {
        $userReference = $this->entityManager->getReference(User::class, $this->user->getId());
        return $this->proposalRepository->findBy(['user' => $userReference],  ['dateStart' => 'DESC'], $itemsPerPage, $offset);

    }

    public function getProposalsCount()
    {
        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('count(proposal.id)');
        $qb->from('App\Model\Proposal', 'proposal');

        return $qb->getQuery()->getSingleScalarResult();
    }

    public function getMineProposalsCount()
    {
        $author = $this->entityManager->getReference(User::class, $this->user->getId());
        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('count(proposal.id)');
        $qb->where('proposal.user =  :user');
        $qb->setParameter('user', $author);
        $qb->from('App\Model\Proposal', 'proposal');

        return $qb->getQuery()->getSingleScalarResult();

    }

    public function getDeletedProposalsCount()
    {
        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('count(proposal.id)');
        $qb->where('proposal.trash = true');
        $qb->from('App\Model\Proposal', 'proposal');
        return $qb->getQuery()->getSingleScalarResult();
    }

    public function findDeletedUsingPaginator($itemsPerPage, $offset)
    {
        return $this->proposalRepository->findBy(['trash' => true],  ['dateStart' => 'DESC'], $itemsPerPage, $offset);

    }

    public function findVVUsingPaginator($itemsPerPage, $offset)
    {
        $group = $this->entityManager->getReference(Group::class, 1);
        return $this->proposalRepository->findBy(['group' => $group, 'trash' => false], ['dateStart' => 'DESC'], $itemsPerPage, $offset);
    }

    public function findSOUsingPaginator($itemsPerPage, $offset)
    {
        $group = $this->entityManager->getReference(Group::class, 2);
        return $this->proposalRepository->findBy(['group' => $group, 'trash' => false], ['dateStart' => 'DESC'], $itemsPerPage, $offset);
    }

    public function getVVProposalsCount()
    {
        $group = $this->entityManager->getReference(Group::class, 1);
        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('count(proposal.id)');
        $qb->where('proposal.group =  :group');
        $qb->andWhere('proposal.trash = false');
        $qb->setParameter('group', $group);
        $qb->from('App\Model\Proposal', 'proposal');
        return $qb->getQuery()->getSingleScalarResult();
    }

    public function getSOProposalsCount()
    {
        $group = $this->entityManager->getReference(Group::class, 2);
        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('count(proposal.id)');
        $qb->where('proposal.group =  :group');
        $qb->andWhere('proposal.trash = false');
        $qb->setParameter('group', $group);
        $qb->from('App\Model\Proposal', 'proposal');
        return $qb->getQuery()->getSingleScalarResult();
    }

    public function getUsersWhoDidNotVote(Proposal $proposal)
    {
        $voters = $this->proposalVotersRepository->findBy(['proposal' => $proposal]);
        $users = array();
        foreach ($voters as $voter) {
            $users[] = $voter->getUser();
        }

        foreach ($proposal->getVotes() as $vote) {
            foreach ($users as $index => $user) {
                if ($vote->getUser()->getId() == $user->getId()) {
                    unset($users[$index]);
                }
            }

        }

        return array_values($users);
    }

    public function search($query)
    {
        $queryResults = $this->proposalRepository->createQueryBuilder('p')
            ->addSelect("MATCH_AGAINST (p.title,p.description, :searchterm 'IN NATURAL MODE') as score")
            ->add('where', 'MATCH_AGAINST(p.title,p.description, :searchterm) > 0.8')
            ->setParameter('searchterm', $query)
            ->orderBy('score', 'desc')
            ->getQuery()
            ->getResult();

        $result = array();
        foreach ($queryResults as $queryResult){
            $result[] = $queryResult[0];
        }
        return $result;
    }


}