<?php

namespace App\Table;



use Nette\Application\UI\Control;

class LogTable extends Control
{
    /**
     * @var
     */
    private $logs;

    public function __construct($logs)
    {
        $this->logs = $logs;
    }

    public function render()
    {
        $template = $this->template;
        $template->setFile(__DIR__ . '/LogTable.latte');
        $template->logs = $this->logs;
        $template->render();
    }
}

interface ILogTableFactory
{
    /**
     * @return LogTable
     */
    public function create($logs);
}
