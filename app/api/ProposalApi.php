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
        $this->resource = json_decode(json_encode($proposal), true);;
        $this->sendResource(IResource::JSON);
    }

    public function actionRead($id)
    {
        if (isset($id)) {
            $proposal = $this->proposalService->findOne($id);
            $this->resource = json_decode(json_encode($proposal), true);;
        } else {
            $proposals = $this->proposalService->findAll();
            $this->resource = json_decode(json_encode($proposals), true);;
        }
        $this->sendResource(IResource::JSON);
    }

    public function actionUpdate($id)
    {
        $proposal = $this->proposalService->updateProposal($this->getInput()->getData(),$id);
        $this->resource = json_decode(json_encode($proposal), true);;
        $this->sendResource(IResource::JSON);
    }

    public function actionDelete($id)
    {
        $proposal = $this->proposalService->deleteProposal($id);
        $this->resource = json_decode(json_encode($proposal), true);
        $this->resource = json_decode(json_encode($proposal), true);
        $this->sendResource(IResource::JSON);
    }

    public function actionReadComments($id)
    {
        $comments = $this->commentService->findByProposalId($id);
        $this->resource = json_decode(json_encode($comments), true);
        $this->sendResource(IResource::JSON);
    }

    public function actionCreateComment($id)
    {
        $text = $this->getInput()->text;
        $result = $this->commentService->addComment($text, $id);
        $this->resource = json_decode(json_encode($result), true);;
        $this->sendResource(IResource::JSON);
    }

    public function actionReadVotes($id)
    {
        $votes = $this->voteService->findByProposalId($id);
        $this->resource = json_decode(json_encode($votes), true);
        $this->sendResource(IResource::JSON);
    }

    public function actionUpdateVote($id)
    {
        $type= $this->getInput()->positive;
        $result = json_decode(json_encode($this->voteService->vote($id, $type)), true);
        $this->resource = $result;
        $this->sendResource(IResource::JSON);

    }

    public function actionReadWatches($id)
    {
        $watches = $this->watchService->findByProposalId($id);
        $this->resource = json_decode(json_encode($watches), true);
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
        $this->resource = json_decode(json_encode($logs), true);
        $this->sendResource(IResource::JSON);
    }
}
