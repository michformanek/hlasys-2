<?php

namespace App\Api;

use App\Service\CommentService;
use App\Service\ProposalService;
use App\Service\VoteService;
use App\Service\WatchService;
use App\Service\LogService;
use Drahak\Restful\IResource;
use Nette\Application\ForbiddenRequestException;

class ProposalApi extends SecuredApi
{
    /**
     * @var ProposalService
     */
    private $proposalService;
    /**
     * @var CommentService
     */
    private $commentService;
    /**
     * @var VoteService
     */
    private $voteService;
    /**
     * @var WatchService
     */
    private $watchService;
    /**
     * @var LogService
     */
    private $logService;


    /**
     * ProposalPresenter constructor.
     * @param ProposalService $proposalService
     * @param CommentService $commentService
     * @param VoteService $voteService
     * @param WatchService $watchService
     * @param LogService $logService
     */
    public function __construct(
        ProposalService $proposalService,
        CommentService $commentService,
        VoteService $voteService,
        WatchService $watchService,
        LogService $logService
    )
    {
        $this->proposalService = $proposalService;
        $this->commentService = $commentService;
        $this->voteService = $voteService;
        $this->watchService = $watchService;
        $this->logService = $logService;
    }

    public function actionCreate()
    {
        $proposal = $this->proposalService->createProposal($this->getInput()->getData());
        $this->resource = Convertor::convertProposal($proposal);
        $this->sendResource(IResource::JSON);
    }

    public function actionRead($id)
    {
        if (isset($id)) {
            $proposal = $this->proposalService->findOne($id);
            $this->resource = Convertor::convertProposal($proposal);
        } else {
            $proposals = $this->proposalService->findAll();
            $this->resource = Convertor::convertProposals($proposals);
        }
        $this->sendResource(IResource::JSON);
    }

    public function actionUpdate($id)
    {
        $proposal = $this->proposalService->updateProposal($this->getInput()->getData(),$id);
        $this->resource = Convertor::convertProposal($proposal);
        $this->sendResource(IResource::JSON);
    }

    public function actionDelete($id)
    {
        $proposal = $this->proposalService->deleteProposal($id);
        $this->resource = Convertor::convertProposal($proposal);
        $this->sendResource(IResource::JSON);
    }

    public function actionReadComments($id)
    {
        $comments = $this->commentService->findByProposalId($id);
        $this->resource = Convertor::convertComments($comments);
        $this->sendResource(IResource::JSON);
    }

    public function actionCreateComment($id)
    {
        $text = $this->getInput()->text;
        $result = Convertor::convertComment($this->commentService->addComment($text, $id));
        $this->resource = $result;
        $this->sendResource(IResource::JSON);
    }

    public function actionReadVotes($id)
    {
        $votes = $this->voteService->findByProposalId($id);
        $this->resource = Convertor::convertVotes($votes);
        $this->sendResource(IResource::JSON);
    }

    public function actionUpdateVote($id)
    {
        $type= $this->getInput()->positive;
        $result = Convertor::convertVote($this->voteService->vote($id, $type));
        $this->resource = $result;
        $this->sendResource(IResource::JSON);

    }

    public function actionReadWatches($id)
    {
        $watches = $this->watchService->findByProposalId($id);
        $this->resource = Convertor::convertWatches($watches);
        $this->sendResource(IResource::JSON);
    }

    public function actionDeleteWatch($id)
    {
        $this->watchService->deleteWatch($id);
    }

    public function actionCreateWatch($id)
    {
        $this->watchService->addWatch($id);
    }

    public function actionReadLogs($id)
    {
        $logs = $this->logService->findByProposalId($id);
        $this->resource = Convertor::convertLogs($logs);
        $this->sendResource(IResource::JSON);
    }
}
