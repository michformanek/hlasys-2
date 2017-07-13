<?php
/**
 * Created by PhpStorm.
 * User: MFormanek
 * Date: 12.06.2017
 * Time: 9:44
 */

namespace App\Presenters;

use App\Components\IProposalDatagridFactory;
use App\Components\IProposalTableFactory;
use App\Forms\CommentForm;
use App\Forms\FilterForm;
use App\Forms\ICommentFormFactory;
use App\Forms\IFilterFormFactory;
use App\Forms\IProposalFormFactory;
use App\Forms\IVoteFormFactory;
use App\Forms\ProposalForm;
use App\Forms\VoteForm;
use App\Model\Watch;
use App\Repository\ProposalRepository;
use App\Repository\WatchRepository;
use Doctrine\ORM\EntityManager;
use Nette\Application\UI\Presenter;
use Nette\Security\User;

class ProposalPresenter extends SecuredPresenter
{

    /** @persistent */
    public $filter = [];

    /**
     * @var ProposalRepository
     */
    private $proposalRepository;
    /**
     * @var ICommentFormFactory
     */
    private $commentFormFactory;
    /**
     * @var IProposalFormFactory
     */
    private $proposalFormFactory;
    /**
     * @var IVoteFormFactory
     */
    private $voteFormFactory;
    /**
     * @var WatchRepository
     */
    private $watchRepository;

    private $currentUser;

    private $em;
    /**
     * @var IProposalDatagridFactory
     */
    private $proposalDatagridFactory;
    /**
     * @var IProposalTableFactory
     */
    private $proposalTableFactory;

    /**
     * @var IFilterFormFactory
     */
    private $filterFormFactory;

    public function __construct(ProposalRepository $proposalRepository,
                                WatchRepository $watchRepository,
                                ICommentFormFactory $commentFormFactory,
                                IFilterFormFactory $filterFormFactory,
                                IProposalFormFactory $proposalFormFactory,
                                IProposalDatagridFactory $proposalDatagridFactory,
                                IProposalTableFactory $proposalTableFactory,
                                IVoteFormFactory $voteFormFactory,
                                User $user,
                                EntityManager $entityManager
    )
    {
        $this->proposalRepository = $proposalRepository;
        $this->commentFormFactory = $commentFormFactory;
        $this->proposalFormFactory = $proposalFormFactory;
        $this->voteFormFactory = $voteFormFactory;
        $this->watchRepository = $watchRepository;
        $this->currentUser = $user;
        $this->em = $entityManager;
        $this->proposalDatagridFactory = $proposalDatagridFactory;
        $this->proposalTableFactory = $proposalTableFactory;
         $this->filterFormFactory = $filterFormFactory;
    }

    public function renderDetail($id)
    {
        $proposal = $this->proposalRepository->find($id);
        if (!$proposal) {
            $this->error('Návrh nebyl nalezen');
        }

        $this->template->proposal = $proposal;
    }

    public function renderEdit($id)
    {
        $proposal = $this->proposalRepository->find($id);
        if (!$proposal) {
            $this->error('Návrh nebyl nalezen'); //FIXME Kontrolovat zda aktualni uzivatel muze editovat zaznam {autor/vyprseni}, kdyz ne, redirect
        }

        $this->template->proposal = $proposal;
    }

    public function renderLogs($id)
    {
        $proposal = $this->proposalRepository->find($id);;
        if (!$proposal) {
            $this->error('Návrh nebyl nalezen');
        }
        $this->template->logs = $proposal->getLogs();
        $this->template->id = $id;
    }

    public function renderCreate()
    {
        $this->setView('edit');
    }

    public function actionWatch($id)
    {
        $proposal = $this->em->getReference('App\Model\Proposal', $id);
        $user = $this->em->getReference('App\Model\User', $this->currentUser->getId());
        $watch = $this->watchRepository->findOneBy(array('user' => $user,'proposal' => $proposal));
        if($watch){
            $this->em->remove($watch);
            $this->em->flush();
            $this->flashMessage('Watch byl odstranen');
            $this->redirect('Proposal:detail',$id);
        }


        $watch = new Watch();
        $watch->setProposal($proposal);
        $watch->setUser($user);
        $this->watchRepository->saveOrUpdate($watch);

        $this->flashMessage('Watch byl přidán');
        $this->redirect('Proposal:detail', $id);
    }

    public function actionNext($id)
    {
        $proposals = $this->proposalRepository->findByFilter($this->filter);
        foreach($proposals as $index=>$value) {
            if ($value->getId() == $id){
                if ( $index == count($proposals) - 1){
                    $this->flashMessage('Jste na konci');
                    $this->redirect('Proposal:detail',$id);
                }
                $proposal = $proposals[$index+1];
                $this->redirect('Proposal:detail', $proposal->getId());
            }
        }
    }

    public function actionPrevious($id)
    {
        $proposals = $this->proposalRepository->findByFilter($this->filter);
        foreach($proposals as $index=>$value) {
            if ($value->getId() == $id){
                if ($index == 0){
                    $this->flashMessage('Jste na začátku');
                    $this->redirect('Proposal:detail',$id);
                }
                $proposal = $proposals[$index-1];
                $this->redirect('Proposal:detail', $proposal->getId());
            }
        }
    }

    public function actionRemove($id)
    {
        $proposal = $this->proposalRepository->find($id);
        $proposal->setTrash(true);
        $this->proposalRepository->saveOrUpdate($proposal);
        $title = $proposal->getTitle();
        $this->flashMessage("Návrh ${$title} byl přemístěn do koše");
        $this->redirect('Proposal:default'); //FIXME Redirect na předchozí stranu
    }

    protected function createComponentCommentForm()
    {
        $control = $this->commentFormFactory->create();
        $control->onCommentSave[] = function (CommentForm $commentForm, $comment) {
            $this->redirect('this');
        };

        return $control;
    }

    protected function createComponentVoteForm()
    {
        $control = $this->voteFormFactory->create();
        $control->onVote[] = function (VoteForm $voteForm, $vote) {
            $this->redirect('this'); //TODO Volitelny redirect na dalsi navrh po odhlasovani, vytahnout z filtru uloženém kdesi v presenteru.
        };
        return $control;
    }

    public function createComponentProposalForm()
    {
        $control = $this->proposalFormFactory->create();

        $control->onProposalSave[] = function (ProposalForm $proposalForm, $proposal) {
            $this->redirect('Proposal:detail', $proposal->getId());
        };

        return $control;
    }

    public function createComponentProposalTable()
    {
        $proposals=$this->proposalRepository->findByFilter($this->filter);
        return $this->proposalTableFactory->create($proposals);
    }

    public function createComponentDeletedProposals()
    {
        $user = $this->em->getReference('App\Model\User', $this->currentUser->getId());
        return $this->proposalTableFactory->create($this->proposalRepository->findBy(array('trash'=>true)));
    }

    public function createComponentMineProposals()
    {
        $user = $this->em->getReference('App\Model\User', $this->currentUser->getId());
        return $this->proposalTableFactory->create($this->proposalRepository->findBy(array('author' => $user)));
    }

    public function createComponentFilterForm()
    {
        $control = $this->filterFormFactory->create($this->filter);
        $control->onFilter[] = function ($form,$filter) {
            $this->filter = $filter;
            $this->redirect('this');
        };

        return $control;
    }



}