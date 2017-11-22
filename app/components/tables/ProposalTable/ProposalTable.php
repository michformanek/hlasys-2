<?php

namespace App\Table;


use App\Service\UserService;
use Nette\Application\UI\Control;

class ProposalTable extends Control
{
    /**
     * @var UserService
     */
    private $userService;

    /**
     * ProposalTable constructor.
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function render($proposals)
    {
        $template = $this->template;
        $template->setFile(__DIR__ . '/ProposalTable.latte');
        $template->proposals = $proposals;
        $template->userReference = $this->userService->getUserReference();
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
