<?php

use App\Repository\UserRepository;
use Nette\Security\Identity;

class MujAuthenticator extends \Nette\Object implements \Nette\Security\IAuthenticator
{
    /**
     * @var UserRepository
     */
    private $userRepository;


    /**
     * Authenticator constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
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