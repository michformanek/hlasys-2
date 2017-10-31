<?php

namespace App\Forms;


use Nette\Application\UI\Control;

class UserSettingsForm extends Control
{
    public function render()
    {
        $template = $this->template;
        $template->setFile(__DIR__ . '/UserSettingsForm.latte');
        $template->render();
    }
}

interface IUserSettingsFormFactory
{
    /**
     * @return UserSettingsForm
     */
    public function create();
}
