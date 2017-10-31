<?php

namespace App\Presenters;

use App\Menu\INavBarFactory;
use App\Menu\ISideBarFactory;
use Kdyby\Replicator\Container;
use Nette;


/**
 * Base presenter for all application presenters.
 */
abstract class BasePresenter extends Nette\Application\UI\Presenter
{

    public function startup()
    {
        parent::startup();
        Container::register();

    }

    /** @var ISideBarFactory @inject */
    public $sideBarFactory;
    /** @var INavBarFactory @inject */
    public $navBarFactory;

    public function createComponentSideBar()
    {
        return $this->sideBarFactory->create();
    }

    public function createComponentNavBar()
    {
        return $this->navBarFactory->create();
    }

}
