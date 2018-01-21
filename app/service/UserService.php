<?php

namespace App\Service;


use App\Model\User;
use Kdyby\Doctrine\EntityManager;

class UserService
{
    /**
     * @var \Nette\Security\User
     */
    private $user;
    private $userRepository;
    private $entityManager;


    /**
     * UserService constructor.
     * @param EntityManager $entityManager
     * @param \Nette\Security\User $user
     */
    public function __construct(
        EntityManager $entityManager,
        \Nette\Security\User $user)
    {
        $this->userRepository = $entityManager->getRepository(User::class);
        $this->entityManager = $entityManager;
        $this->user = $user;
    }

    public function getUserReference()
    {
        return $this->entityManager->getReference(User::class, $this->user->getId());
    }
}