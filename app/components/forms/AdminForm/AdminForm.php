<?php

namespace App\Forms;


use Nette\Application\UI\Control;

class AdminForm extends Control
{
    public function render()
    {
        $template = $this->template;
        $template->setFile(__DIR__ . '/AdminForm.latte');
        $template->render();
    }
}

interface IAdminFormFactory
{
    /**
     * @return AdminForm
     */
    public function create();
}
