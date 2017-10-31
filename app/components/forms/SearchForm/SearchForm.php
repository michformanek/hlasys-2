<?php

namespace App\Forms;


use Nette\Application\UI\Control;

class SearchForm extends Control
{
    public function render()
    {
        $template = $this->template;
        $template->setFile(__DIR__ . '/SearchForm.latte');
        $template->render();
    }
}

interface ISearchFormFactory
{
    /**
     * @return SearchForm
     */
    public function create();
}
