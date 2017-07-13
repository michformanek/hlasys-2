<?php

namespace App\Forms;

use App\Model\Comment;
use App\Repository\CommentRepository;
use App\Repository\ProposalRepository;
use App\Repository\UserRepository;
use DateTime;
use Nette\Application\UI;
use Nette\Security\User;

class CommentForm extends UI\Control
{
    /**
     * @var CommentRepository
     */
    private $commentRepository;
    /**
     * @var ProposalRepository
     */
    private $proposalRepository;
    /**
     * @var User
     */
    private $currentUser;
    /**
     * @var UserRepository
     */
    private $userRepository;

    public $onCommentSave;

    /**
     * LoginForm constructor.
     * @param CommentRepository $commentRepository
     * @param ProposalRepository $proposalRepository
     * @param User $currentUser
     */
    public function __construct(CommentRepository $commentRepository, ProposalRepository $proposalRepository, UserRepository $userRepository, User $currentUser)
    {
        parent::__construct();
        $this->commentRepository = $commentRepository;
        $this->proposalRepository = $proposalRepository;
        $this->currentUser = $currentUser;
        $this->userRepository = $userRepository;
    }

    /**
     * @return Form
     */
    protected function createComponentCommentForm()
    {
        $form = new UI\Form;
        $form->addTextArea('text')
            ->setRequired('Prosím zadejte text komentáře');
        $form->addSubmit('send', 'Přidat komentář');
        $form->onSuccess[] = [$this, 'commentFormSucceeded'];
        return $form;
    }

    //FIXME: čas vytvoření, autor příspěvku, editace
    public function commentFormSucceeded($form, $values)
    {
        $this->currentUser->login('1', '1234');
        $comment = new Comment();
        $proposalId = $this->presenter->getParameter('id');
        $comment->setProposal($this->proposalRepository->find($proposalId));
        $comment->setText($values->text);
        $comment->setDate(new DateTime());
        $userId = $this->currentUser->id ? $this->currentUser->id : 1; //FIXME: Pouze přihlášené!!!
        $comment->setUser($this->userRepository->find($userId));
        $this->commentRepository->saveOrUpdate($comment);
        $this->flashMessage('Děkuji za komentář', 'success');
        $this->onCommentSave($this, $comment);
    }

    public function render()
    {
        $this->template->render(__DIR__ . '/CommentForm.latte');
    }
}

interface ICommentFormFactory
{
    /**
     * @param int $id
     * @return CommentForm
     */
    function create();
}