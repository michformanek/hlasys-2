<?php

namespace App\Table;


use Nette\Application\UI\Control;

class ProposalTable extends Control
{
    public function render($proposals)
    {
        $template = $this->template;
        $template->setFile(__DIR__ . '/ProposalTable.latte');
        $template->proposals = $proposals;
        $template->render();
    }
}
interface IProposalTableFactory
{
    /**
     * @return ProposalTable
     */
    public function create();
}
