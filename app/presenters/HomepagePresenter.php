<?php

namespace App\Presenters;

use App\Repository\ProposalRepository;
use App\Service\ProposalService;
use App\Table\IProposalDatagridFactory;
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
    /**
     * @var IProposalDatagridFactory
     */
    private $proposalDatagridFactory;
    /**
     * @var ProposalRepository
     */
    private $proposalRepository;


    /**
     * HomepagePresenter constructor.
     * @param ProposalService $proposalService
     * @param IProposalTableFactory $proposalTableFactory
     * @param IProposalDatagridFactory $proposalDatagridFactory
     * @param ProposalRepository $proposalRepository
     */
    public function __construct(
        ProposalService $proposalService,
        IProposalTableFactory $proposalTableFactory,
        IProposalDatagridFactory $proposalDatagridFactory,
        ProposalRepository $proposalRepository
    )
    {
        $this->proposalService = $proposalService;
        $this->proposalTableFactory = $proposalTableFactory;
        $this->proposalDatagridFactory = $proposalDatagridFactory;
        $this->proposalRepository = $proposalRepository;
    }


    public function createComponentProposalDatagrid(){
        $control = $this->proposalDatagridFactory->create($this->proposalRepository->createQueryBuilder('p'));
        return $control;
    }


}
