<?php

namespace App\Service;


use App\Model\Group;
use App\Model\Vote;
use App\Model\VoteType;
use App\Repository\VoteTypeRepository;
use Doctrine\ORM\EntityManager;
use Tracy\Debugger;

class VoteTypeService
{
    /**
     * @var VoteTypeRepository
     */
    private $voteTypeRepository;

    /**
     * @var EntityManager
     */
    private $entityManager;
    /**
     * @var GroupService
     */
    private $groupService;

    public function __construct(
        GroupService $groupService,
        VoteTypeRepository $voteTypeRepository,
        EntityManager $entityManager
    )
    {
        $this->voteTypeRepository = $voteTypeRepository;
        $this->entityManager = $entityManager;
        $this->groupService = $groupService;
    }

    public function find($id)
    {
        return $this->voteTypeRepository->find($id);
    }

    /**
     * Creates array of options for form select.
     * @return array of options
     */
    public function getVoteTypeOptions()
    {
        $voteTypes = $this->voteTypeRepository->findAll();
        $result = array();
        foreach ($voteTypes as $voteType) {
            $result[$voteType->getGroup()->getName()][$voteType->getId()] = $voteType->getText();
        }
        return $result;
    }

    public function findAll()
    {
        return $this->voteTypeRepository->findAll();
    }

    public function delete($id)
    {
        $voteType = $this->voteTypeRepository->find($id);
        $voteType->setActive(false);
        $this->entityManager->persist($voteType);
        $this->entityManager->flush();
        return $voteType;
    }

    public function create($data)
    {
        $group = $this->entityManager->getReference(Group::class, $data['group_id']);

        $voteType = new VoteType();
        $voteType->setText($data['text']);
        $voteType->setGroup($group);
        $voteType->setPercentsToPass($data['percents_topass']);
        $voteType->setUsersToPass($data['users_topass']);
        $voteType->setActive($data['active']);

        $this->entityManager->persist($voteType);
        $this->entityManager->flush();

        return $voteType;
    }
}