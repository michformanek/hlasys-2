<?php

namespace App\Forms;


use Nette\Application\UI\Control;
use Nette\Application\UI\Form;

class SearchForm extends Control
{

    public $onSearch;

    public function render()
    {
        $template = $this->template;
        $template->setFile(__DIR__ . '/SearchForm.latte');
        $template->render();
    }

    public function createComponentSearchForm()
    {

        $form = new Form;
        $form->addText('text');
        $form->onSuccess[] = [$this, 'success'];
        return $form;
    }

    public function success($form, $values)
    {
        $this->onSearch($values['text']);
    }
}

interface ISearchFormFactory
{
    /**
     * @return SearchForm
     */
    public function create();
}
