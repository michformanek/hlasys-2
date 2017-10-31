<?php

namespace App\Api;


use Drahak\Restful\Application\UI\ResourcePresenter;

class SecuredApi extends ResourcePresenter
{

    public function startup()
    {
        parent::startup();
        if (!$this->user->isLoggedIn()) {
            $this->user->login('1', '1234');
        }
    }

}