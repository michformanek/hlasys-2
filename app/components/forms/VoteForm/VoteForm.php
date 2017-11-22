<?php

namespace App\Forms;


use App\Service\ProposalService;
use App\Service\VoteService;
use Nette\Application\UI\Control;

class VoteForm extends Control
{

    /**
     * @var
     */
    private $proposalId;
    /**
     * @var VoteService
     */
    private $voteService;

    public $onVote;
    /**
     * @var ProposalService
     */
    private $proposalService;

    public function __construct(
        $proposalId,
        VoteService $voteService,
        ProposalService $proposalService
    )
    {
        $this->proposalId = $proposalId;
        $this->voteService = $voteService;
        $this->proposalService = $proposalService;
    }

    public function render()
    {
        $template = $this->template;
        $template->setFile(__DIR__ . '/VoteForm.latte');
        $template->vote = $this->voteService->getVoteOfCurrentUser($this->proposalId);
        $template->proposal = $this->proposalService->findOne($this->proposalId);
        $template->render();
    }

    public function handleVotePositive()
    {
        $this->voteService->vote($this->proposalId,true);
        $this->onVote();
    }

    public function handleVoteNegative()
    {
        $this->voteService->vote($this->proposalId,false);
        $this->onVote();
    }
}

interface IVoteFormFactory
{
    /**
     * @return VoteForm
     */
    public function create($proposalId);
}
