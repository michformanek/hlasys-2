<?php

namespace App\Forms;


use Nette\Application\UI\Control;

class FilterForm extends Control
{
    public function render()
    {
        $template = $this->template;
        $template->setFile(__DIR__ . '/FilterForm.latte');
        $template->render();
    }
}

interface IFilterFormFactory
{
    /**
     * @return FilterForm
     */
    public function create();
}
