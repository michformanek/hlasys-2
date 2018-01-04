<?php

namespace App\Presenters;


use App\Forms\ICommentFormFactory;
use App\Forms\IReplicatorFormControl;
use App\Forms\IVoteFormFactory;
use App\Forms\IWatchFormFactory;
use App\Forms\WatchForm;
use App\Model\Proposal;
use App\Service\CommentService;
use App\Service\GroupService;
use App\Service\MailService;
use App\Service\ProposalService;
use App\Service\VoteTypeService;
use App\Table\ICommentTableFactory;
use App\Table\IItemTableFactory;
use App\Table\ILogTableFactory;
use App\Table\IProposalTableFactory;
use App\Table\IVoteTableFactory;
use App\Table\IWatchTableFactory;
use Nette\Application\UI\Form;
use Nette\Application\UI\Multiplier;
use IPub\VisualPaginator\Components as VisualPaginator;
use Tracy\Debugger;

class ProposalPresenter extends SecuredPresenter
{
    /**
     * @var ProposalService
     */
    private $proposalService;
    /**
     * @var IProposalTableFactory
     */
    private $proposalsTableFactory;
    /**
     * @var IWatchFormFactory
     */
    private $watchFormFactory;
    /**
     * @var ICommentFormFactory
     */
    private $commentFormFactory;
    /**
     * @var ICommentTableFactory
     */
    private $commentTableFactory;
    /**
     * @var ILogTableFactory
     */
    private $logTableFactory;
    /**
     * @var IVoteFormFactory
     */
    private $voteFormFactory;
    /**
     * @var IVoteTableFactory
     */
    private $voteTableFactory;
    /**
     * @var MailService
     */
    private $mailService;

    private $proposal;
    private $comment;
    /**
     * @var IWatchTableFactory
     */
    private $watchTableFactory;
    /**
     * @var GroupService
     */
    private $groupService;
    /**
     * @var VoteTypeService
     */
    private $voteTypeService;
    /**
     * /**
     * @var IItemTableFactory
     */
    private $itemTableFactory;
    /**
     * @var CommentService
     */
    private $commentService;
    /**
     * @var IReplicatorFormControl
     */
    private $replicatorFormControl;

    public function __construct(
        IProposalTableFactory $proposalsTableFactory,
        IWatchFormFactory $watchFormFactory,
        IWatchTableFactory $watchTableFactory,
        ICommentFormFactory $commentFormFactory,
        ICommentTableFactory $commentTableFactory,
        ILogTableFactory $logTableFactory,
        IVoteFormFactory $voteFormFactory,
        IItemTableFactory $itemTableFactory,
        IVoteTableFactory $voteTableFactory,
        IReplicatorFormControl $replicatorFormControl,
        ProposalService $proposalService,
        MailService $mailService,
        GroupService $groupService,
        VoteTypeService $voteTypeService,
        CommentService $commentService
    )
    {
        $this->proposalsTableFactory = $proposalsTableFactory;
        $this->watchFormFactory = $watchFormFactory;
        $this->commentFormFactory = $commentFormFactory;
        $this->commentTableFactory = $commentTableFactory;
        $this->logTableFactory = $logTableFactory;
        $this->voteFormFactory = $voteFormFactory;
        $this->voteTableFactory = $voteTableFactory;
        $this->proposalService = $proposalService;
        $this->mailService = $mailService;
        $this->watchTableFactory = $watchTableFactory;
        $this->groupService = $groupService;
        $this->voteTypeService = $voteTypeService;
        $this->itemTableFactory = $itemTableFactory;
        $this->commentService = $commentService;
        $this->replicatorFormControl = $replicatorFormControl;
    }

    public function renderDefault()
    {
        $visualPaginator = $this['paginator'];
        $paginator = $visualPaginator->getPaginator();
        $count = $this->proposalService->getProposalsCount();
        $paginator->itemCount = $count;
        $proposals = $this->proposalService->findUsingPaginator($paginator->itemsPerPage, $paginator->offset);
        $this->template->proposals = $proposals;
    }

    public function renderDeleted()
    {
        $visualPaginator = $this['paginator'];
        $paginator = $visualPaginator->getPaginator();
        $count = $this->proposalService->getDeletedProposalsCount();
        $paginator->itemCount = $count;
        $proposals = $this->proposalService->findDeletedUsingPaginator($paginator->itemsPerPage, $paginator->offset);
        $this->template->proposals = $proposals;
    }

    public function renderMine()
    {
        $visualPaginator = $this['paginator'];
        $paginator = $visualPaginator->getPaginator();
        $count = $this->proposalService->getMineProposalsCount();
        $paginator->itemCount = $count;
        $proposals = $this->proposalService->findMineUsingPaginator($paginator->itemsPerPage, $paginator->offset);
        $this->template->proposals = $proposals;
    }

    public function renderVv()
    {
        $visualPaginator = $this['paginator'];
        $paginator = $visualPaginator->getPaginator();
        $count = $this->proposalService->getVVProposalsCount();
        $paginator->itemCount = $count;
        $proposals = $this->proposalService->findVVUsingPaginator($paginator->itemsPerPage, $paginator->offset);
        $this->template->proposals = $proposals;
    }

    public function renderSo()
    {
        $visualPaginator = $this['paginator'];
        $paginator = $visualPaginator->getPaginator();
        $count = $this->proposalService->getSOProposalsCount();
        $paginator->itemCount = $count;
        $proposals = $this->proposalService->findSOUsingPaginator($paginator->itemsPerPage, $paginator->offset);
        $this->template->proposals = $proposals;
    }

    public function renderSearch($query){
        $visualPaginator = $this['paginator'];
        $paginator = $visualPaginator->getPaginator();
        $results = $this->proposalService->search($query);
        $paginator->itemCount = count($results);
        $this->template->proposals = $results;
        $this->template->query = $query;
    }

    public function renderCreate()
    {
        $this->setView('edit');
        $this->proposal = new Proposal();
    }

    public function renderEdit($id)
    {
        $this['proposalForm']->edit($id);
    }

    public function renderDetail($id)
    {
        $proposal = $this->proposalService->findOne($id);
        if ($proposal == null) {
            //fixme 404
        }
        $this->proposal = $proposal;
        $this->template->proposal = $this->proposal;
//        $this->template->parsedown = new \Parsedown();
        $isAllowed = $this->user->isInRole('VV') || $this->user->isInRole('admin');
        $this->template->canEdit = ($this->nehlasovano($proposal) && $this->user->isInRole($proposal->getGroup()->getName()))
            || ($isAllowed && $this->nehlasovano($proposal));
    }

    public function actionTrash($id)
    {
        $this->proposalService->deleteProposal($id);
        $this->redrawControl('proposalTable');
    }

    public function actionRemoveComment($commentId)
    {
        $this->commentService->deleteComment($commentId);
        $this->redrawControl('commentTable');
    }

    public function actionEditComment($commentId)
    {
        $this['commentForm']->edit($commentId);
        $this->redrawControl('commentForm');
    }

    public function createComponentProposalTable()
    {
        return $this->proposalsTableFactory->create();
    }

    public function createComponentVoteTable()
    {
        $votes = $this->proposal->getVotes();
        $didNotVote = $this->proposalService->getUsersWhoDidNotVote($this->proposal);
        return $this->voteTableFactory->create($votes, $didNotVote);
    }

    public function createComponentWatchTable()
    {
        $watches = $this->proposal->getWatches();
        return $this->watchTableFactory->create($watches);
    }

    public function createComponentLogTable()
    {
        $logs = $this->proposal->getLogs();
        return $this->logTableFactory->create($logs);
    }

    public function createComponentItemTable()
    {
        $items = $this->proposal->getItems();
        return $this->itemTableFactory->create($items);
    }

    public function createComponentCommentTable()
    {
        $control = $this->commentTableFactory->create();
        $control->onRemove[] = function () {
            $this->redrawControl('commentTable');
            $this->redrawControl('logTable');
        };
        $control->onEdit[] = function ($commentId) {
            $this->actionEditComment($commentId);
        };
        return $control;
    }

    public function createComponentVoteForm()
    {
        $proposalId = $this->getParameter('id');
        $control = $this->voteFormFactory->create($proposalId);
        $control->onVote[] = function () {
            $this->redrawControl('logTable');
            $this->redrawControl('voteTable');
            $this->redrawControl('voteFormTop');

            //$this->redirect('this');
            //TODO
            // Volitelny redirect na dalsi navrh po odhlasovani,
            // vytahnout z filtru uloženém kdesi v presenteru.
        };

        return $control;
    }


    public function createComponentCommentForm()
    {
        $proposalId = $this->getParameter('id');
        $control = $this->commentFormFactory->create($this->comment, $proposalId);
        $control->onCommentSave[] = function () {
            $this->redrawControl('commentTable');
            $this->redrawControl('logTable');
            $this->redrawControl('commentForm');
        };
        return $control;
    }

    public function createComponentWatchForm()
    {
        $proposalId = $this->getParameter('id');
        $control = $this->watchFormFactory->create($proposalId);
        $control->onWatchChanged[] = function ($proposalId) {
            $this->redrawControl('watchTable');
            $this->redrawControl('logTable');
            $this->redrawControl('watchFormBottom');
            $this->redrawControl('watchFormTop');
            //    $this->redirect('Proposal:detail', $proposalId);
        };
        return $control;
    }

    public function createComponentProposalForm()
    {
        $control = $this->replicatorFormControl->create();
//        $control->onProposalSave[] = function () {
//
//        };
        return $control;
    }

    /**
     * @param $proposal
     * @return bool
     */
    public function nehlasovano($proposal)
    {
        return count($proposal->getVotes()) < 1;
    }

    /**
     * Create proposals paginator
     *
     * @return VisualPaginator\Control
     */
    protected function createComponentPaginator()
    {
        $control = new VisualPaginator\Control;
        $control->setTemplateFile(__DIR__ . '/templates/paginator.latte');
        $control->disableAjax();
        $control->getPaginator()->itemsPerPage = 20;
        return $control;
    }

}