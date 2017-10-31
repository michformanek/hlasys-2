<?php

namespace App\Menu;


use Nette\Application\UI\Control;

class SideBar extends Control
{
    public function render()
    {
        $template = $this->template;
        $template->setFile(__DIR__ . '/SideBar.latte');
        $template->render();
    }
}

interface ISideBarFactory
{
    /**
     * @return SideBar
     */
    public function create();
}
