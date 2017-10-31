<?php

namespace App\Presenters;

use Nette;

/**
 * Base presenter for all non-public application presenters.
 */
abstract class SecuredPresenter extends BasePresenter
{

    public function startup()
    {
        parent::startup();

        if (!$this->user->isLoggedIn()) {
            $this->user->login('1', '1234');
//            if ($this->user->logoutReason === Nette\Security\IUserStorage::INACTIVITY) {
//                $this->flashMessage('You have been signed out due to inactivity. Please sign in again.');
//            }
//            $this->redirect('Login:default', ['backlink' => $this->storeRequest()]);
        }
    }
}

