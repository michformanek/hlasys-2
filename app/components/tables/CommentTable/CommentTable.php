<?php

namespace App\Table;


use App\Service\CommentService;
use Nette\Application\UI\Control;

class CommentTable extends Control
{
    public $onRemove;
    public $onEdit;
    /**
     * @var CommentService
     */
    private $commentService;

    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    public function render($proposalId)
    {
        $template = $this->template;
        $template->setFile(__DIR__ . '/CommentTable.latte');
        $template->comments = $this->commentService->findByProposalId($proposalId);
        $template->render();
    }

    public function handleRemove($commentId)
    {
        $this->commentService->deleteComment($commentId);
        $this->onRemove($commentId);
    }


    public function handleEdit($commentId)
    {
        $this->onEdit($commentId);
    }
}

interface ICommentTableFactory
{
    /**
     * @return CommentTable
     */
    public function create();
}
