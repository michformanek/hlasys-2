<?php

namespace App\Api;


use App\Service\CommentService;
use Drahak\Restful\IResource;

class CommentApi extends SecuredApi
{
    /**
     * @var CommentService
     */
    private $commentService;


    /**
     * CommentApi constructor.
     * @param CommentService $commentService
     */
    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    public function actionRead($id)
    {
        if (isset($id)) {
            $comment = $this->commentService->findOne($id);
            $this->resource = json_decode(json_encode($comment), true);
        }
        else{
            $comments = $this->commentService->findAll();
            $this->resource = json_decode(json_encode($comments), true);
        }
        $this->sendResource(IResource::JSON);
    }

    public function actionUpdate($id)
    {
        $text = $this->getInput()->text;
        $result = json_decode(json_encode($this->commentService->updateComment($text,$id)), true);
        $this->resource = $result;
        $this->sendResource(IResource::JSON);
    }

    public function actionDelete($id)
    {
        $this->commentService->deleteComment($id);
    }

}