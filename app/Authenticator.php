<?php

use Nette\Security\Identity;

class MujAuthenticator extends \Nette\Object implements \Nette\Security\IAuthenticator
{

    private $userRepository;
    private $entityManager;

    /**
     * MujAuthenticator constructor.
     * @param \Kdyby\Doctrine\EntityManager $entityManager
     */
    public function __construct(\Kdyby\Doctrine\EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->userRepository = $entityManager->getRepository(\App\Model\User::class);
    }


    function authenticate(array $credentials)
    {
        list($username, $password) = $credentials;
        $user = $this->userRepository->find($username);
        return new Identity($user->getId(), $this->getUserRoles($user), ['username' => $user->getUsername(), 'email' => $user->getEmail()]);
    }

    function getUserRoles(\App\Model\User $user)
    {
        $roles = array();
        $now = new \DateTime();
        foreach ($user->getUserGroup() as $group) {
            if ($now >= $group->getFrom() && $now <= $group->getTo()) {
                $roles[] = $group->getGroup()->getName();
            }
        }
        return $roles;
    }

}