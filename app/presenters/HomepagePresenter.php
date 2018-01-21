<?php

namespace App\Presenters;

use App\Model\Proposal;
use App\Service\ProposalService;
use App\Table\IProposalDatagridFactory;
use App\Table\IProposalTableFactory;
use Kdyby\Doctrine\EntityManager;


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
    private $proposalRepository;
    private $entityManager;


    /**
     * HomepagePresenter constructor.
     * @param ProposalService $proposalService
     * @param IProposalTableFactory $proposalTableFactory
     * @param IProposalDatagridFactory $proposalDatagridFactory
     * @param \Kdyby\Doctrine\EntityManager $entityManager
     */
    public function __construct(
        ProposalService $proposalService,
        IProposalTableFactory $proposalTableFactory,
        IProposalDatagridFactory $proposalDatagridFactory,
        EntityManager $entityManager
    )
    {
        $this->proposalService = $proposalService;
        $this->proposalTableFactory = $proposalTableFactory;
        $this->proposalDatagridFactory = $proposalDatagridFactory;
        $this->proposalRepository = $entityManager->getRepository(Proposal::class);
        $this->entityManager = $entityManager;
    }


    public function createComponentProposalDatagrid(){
        $control = $this->proposalDatagridFactory->create($this->proposalRepository->createQueryBuilder('p'));
        return $control;
    }


}
