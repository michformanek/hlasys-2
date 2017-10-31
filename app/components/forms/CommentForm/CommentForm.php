<?php

namespace App\Forms;

use App\Service\CommentService;
use DateTime;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;

class CommentForm extends Control
{
    /**
     * @var CommentService
     */
    private $commentService;

    public $onCommentSave;

    public function __construct(
        CommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    public function createComponentCommentForm()
    {

        $form = new Form;
        $form->addHidden('id');
        $form->addHidden('userId');

        $form->addHidden('proposalId')
            ->setDefaultValue($this->getPresenter()->getParameter('id'));

        $form->addTextArea('text', 'Text komentáře')
            ->setRequired('Vyplňte, prosím, text komentáře.');

        $form->addSubmit('send', 'Uložit');

        $form->onSuccess[] = [$this, 'success'];
        return $form;
    }

    public function success($form, $values)
    {
        $this->commentService->createComment($values);
        $this['commentForm']->setValues(['text' => '', 'id' => '']);
        $this->onCommentSave();
    }

    public function render()
    {
        $template = $this->template;
        $template->setFile(__DIR__ . '/CommentForm.latte');
        $template->render();
    }

    public function edit($commentId)
    {
        $comment = $this->commentService->findOne($commentId);
        $values = array();
        $values['id'] = $comment->getId();
        $values['proposalId'] = $comment->getProposal()->getId();
        $values['text'] = $comment->getText();
        $values['userId'] = $comment->getUser()->getId();
        $this['commentForm']->setDefaults($values);
    }
}

interface ICommentFormFactory
{
    /**
     * @return CommentForm
     */
    public function create();
}
