<?php

namespace App\Service;


use App\Model\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;

class UserService
{
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var EntityManager
     */
    private $entityManager;
    /**
     * @var \Nette\Security\User
     */
    private $user;


    /**
     * UserService constructor.
     * @param UserRepository $userRepository
     * @param EntityManager $entityManager
     * @param \Nette\Security\User $user
     */
    public function __construct(UserRepository $userRepository, EntityManager $entityManager, \Nette\Security\User $user)
    {
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
        $this->user = $user;
    }

    public function getUserReference(){
        return $this->entityManager->getReference(User::class, $this->user->getId());
    }
}