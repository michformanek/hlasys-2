<?php

namespace App\Presenters;

use App\Repository\ProposalRepository;
use App\Service\ProposalService;
use App\Table\IProposalTableFactory;
use Nette;
use Parsedown;


class HomepagePresenter extends SecuredPresenter
{

    /**
     * @var ProposalService
     */
    private $proposalService;
    /**
     * @var IProposalTableFactory
     */
    private $proposalTableFactory;


    public function __construct(
        ProposalService $proposalService,
        IProposalTableFactory $proposalTableFactory
    )
    {
        $this->proposalService = $proposalService;
        $this->proposalTableFactory = $proposalTableFactory;
    }

    public function renderDefault()
    {
        //FIXME Zobrazit neodhlasované
        $this->template->proposals = $this->proposalService->findAll();
    }

    public function createComponentProposalTable()
    {
        $control = $this->proposalTableFactory->create();
        return $control;
    }


}
