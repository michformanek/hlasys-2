<?php

namespace App\Menu;


use App\Forms\ISearchFormFactory;
use Nette\Application\UI\Control;

class NavBar extends Control
{

    /**
     * @var ISearchFormFactory
     */
    private $searchFormFactory;

    public function __construct(ISearchFormFactory $searchFormFactory)
    {
        $this->searchFormFactory = $searchFormFactory;
    }


    public function render()
    {
        $template = $this->template;
        $template->setFile(__DIR__ . '/NavBar.latte');
        $template->render();
    }

    public function createComponentSearchForm()
    {
        $control = $this->searchFormFactory->create();
        $control->onSearch[] = function ($query) {
            $presenter = $this->getPresenter();
            $presenter->redirect('Proposal:search', ['query' => $query]);
        };
        return $control;
    }
}

interface INavBarFactory
{
    /**
     * @return NavBar
     */
    public function create();
}
