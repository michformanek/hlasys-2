<?php

namespace App\Service;


use App\Constants\LogMessages;
use App\Model\Comment;
use App\Model\Proposal;
use App\Model\User;
use App\Repository\CommentRepository;
use DateTime;
use Doctrine\ORM\EntityManager;

class CommentService
{

    /**
     * @var EntityManager
     */
    private $entityManager;
    /**
     * @var CommentRepository
     */
    private $commentRepository;
    /**
     * @var \Nette\Security\User
     */
    private $user;
    /**
     * @var LogService
     */
    private $logService;
    /**
     * @var MailService
     */
    private $mailService;

    public function __construct(
        EntityManager $entityManager,
        CommentRepository $commentRepository,
        LogService $logService,
        MailService $mailService,
        \Nette\Security\User $user
    )
    {

        $this->entityManager = $entityManager;
        $this->commentRepository = $commentRepository;
        $this->user = $user;
        $this->logService = $logService;
        $this->mailService = $mailService;
    }

    /**
     * @param $commentId
     * @return null|Comment
     */
    public function findOne($commentId)
    {
        return $this->commentRepository->find($commentId);
    }

    public function saveComment($comment)
    {
        $this->entityManager->persist($comment);
        $this->entityManager->flush();
    }

    //deprecated
    public function createComment($values)
    {
        $isNew = false;
        $userId = $this->user->getId();
        $commentId = $values['id'];
        $comment = $this->findOne($commentId);
        if ($comment == null) {
            $comment = new Comment();
            $isNew = true;
        }
        $proposal = $this->entityManager->getReference(Proposal::class, $values['proposalId']);
        $comment->setProposal($proposal);
        $comment->setDate(new DateTime());
        $comment->setText($values['text']);
        $comment->setUser($this->entityManager->getReference(User::class, empty($values['userId']) ? $userId : $values['userId']));
        $this->saveComment($comment);
        $this->logService->logCommentAdded($proposal,$comment);

        if ($isNew) {
            $this->mailService->sendCommentAdded($comment);
        } else {
            $this->mailService->sendCommentEdited($comment);
        }
    }

    public function addComment($text, $proposalId)
    {
        $proposal = $this->entityManager->getReference(Proposal::class, $proposalId);
        $user = $this->entityManager->getReference(User::class, $this->user->getId());

        $comment = new Comment();
        $comment->setText($text);
        $comment->setProposal($proposal);
        $comment->setDate(new DateTime());
        $comment->setUser($user);
        $this->saveComment($comment);
        $this->logService->logCommentAdded($proposal,$comment);
        $this->mailService->sendCommentAdded($comment);
        $this->entityManager->refresh($comment);
        return $comment;
    }

    /**
     * @param $text
     * @param $commentId
     * @return null|Comment
     */
    public function updateComment($text, $commentId)
    {
        $comment = $this->commentRepository->find($commentId);
        $comment->setText($text);
        $this->saveComment($comment);
        $this->logService->logCommentEdited($comment);
        $this->mailService->sendCommentEdited($comment);
        $this->entityManager->refresh($comment);
        return $comment;
    }


    public function deleteComment($commentId)
    {
        $comment = $this->commentRepository->find($commentId);
        $this->logService->logCommentDeleted($comment);
        $this->mailService->sendCommentDeleted($comment);
        $this->entityManager->remove($comment);
        $this->entityManager->flush();
    }

    public function findByProposalId($proposalId)
    {
        $proposalReference = $this->entityManager->getReference(Proposal::class, $proposalId);
        return $this->commentRepository->findBy(['proposal' => $proposalReference]);
    }

    public function findAll()
    {
        return $this->commentRepository->findAll();
    }
}