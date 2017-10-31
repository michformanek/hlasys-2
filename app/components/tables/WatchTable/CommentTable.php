<?php

namespace App\Table;


use Nette\Application\UI\Control;

class WatchTable extends Control
{
    /**
     * @var
     */
    private $watches;

    public function __construct($watches)
    {
        $this->watches = $watches;
    }

    public function render()
    {
        $template = $this->template;
        $template->setFile(__DIR__ . '/WatchTable.latte');
        $template->watches = $this->watches;
        $template->render();
    }
}

interface IWatchTableFactory
{
    /**
     * @return WatchTable
     */
    public function create($watches);
}
