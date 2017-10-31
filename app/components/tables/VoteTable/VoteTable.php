<?php

namespace App\Table;


use Nette\Application\UI\Control;

class VoteTable extends Control
{
    /**
     * @var
     */
    private $votes;

    public function __construct($votes)
    {
        $this->votes = $votes;
    }

    public function render()
    {
        $template = $this->template;
        $template->setFile(__DIR__ . '/VoteTable.latte');
        $template->votes = $this->votes;
        $template->render();
    }
}
interface IVoteTableFactory
{
    /**
     * @return VoteTable
     */
    public function create($votes);
}
