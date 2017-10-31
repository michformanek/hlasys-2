<?php

namespace App\Menu;


use Nette\Application\UI\Control;

class NavBar extends Control
{
    public function render()
    {
        $template = $this->template;
        $template->setFile(__DIR__ . '/NavBar.latte');
        $template->render();
    }
}

interface INavBarFactory
{
    /**
     * @return NavBar
     */
    public function create();
}
