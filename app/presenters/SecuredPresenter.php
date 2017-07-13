<?php
/**
 * User: Michal Formánek
 * Date: 5.2.17
 * Time: 11:04
 */

namespace App\Presenters;

use Nette;

/**
 * Base presenter for all non-public application presenters.
 */
abstract class SecuredPresenter extends BasePresenter
{
    /** @var \DK\Menu\UI\IControlFactory @inject */
    public $menuFactory;

    /**
     * @return \DK\Menu\UI\Control
     */
    protected function createComponentMenu()
    {
        return $this->menuFactory->create();
    }


    protected function startup()
    {
        parent::startup();
//
//        if (!$this->user->isLoggedIn()) {
//            if ($this->user->logoutReason === Nette\Security\IUserStorage::INACTIVITY) {
//                $this->flashMessage('You have been signed out due to inactivity. Please sign in again.');
//            }
//            $this->redirect('Login:default', ['backlink' => $this->storeRequest()]);
//        }
    }

}

