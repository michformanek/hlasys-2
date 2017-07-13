<?php

namespace App\Presenters;

use App\Repository\ProposalRepository;
use Nette;


class HomepagePresenter extends SecuredPresenter
{

    /**
     * @var ProposalRepository
     */
    private $proposalRepository;

    public function __construct(ProposalRepository $proposalRepository)
    {
        $this->proposalRepository = $proposalRepository;
        //FIXME
    }

    public function renderDefault(){
        $this->template->proposals = $this->proposalRepository->findAll();
    }
}
