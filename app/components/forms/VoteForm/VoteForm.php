<?php

namespace App\Forms;


use App\Model\Vote;
use App\Repository\VoteRepository;
use Doctrine\ORM\EntityManager;
use Nette\Application\UI;
use Nette\Security\User;

class VoteForm extends UI\Control
{
    public $onVote;
    /**
     * @var User
     */
    private $currentUser;
    /**
     * @var ProposalRepository
     */
    private $proposalRepository;
    /**
     * @var VoteRepository
     */
    private $voteRepository;
    /**
     * @var EntityManager
     */
    private $em;

    public function __construct(User $currentUser,VoteRepository $voteRepository,EntityManager $em)
    {
        parent::__construct();
        $this->currentUser = $currentUser;
        $this->voteRepository = $voteRepository;
        $this->em = $em;
    }

    /**
     * @return Form
     */
    protected function createComponentVoteForm()
    {
        $form = new UI\Form;
        $form->addSubmit('positive', 'Pro navrh')
            ->onClick[] = [$this, 'voteFormSucceeded']
        ;
        $form->addSubmit('negative', 'Proti navrhu')
            ->onClick[] = [$this, 'voteFormSucceeded']
        ;


        return $form;
    }

    public function voteFormSucceeded(\Nette\Forms\Controls\SubmitButton $button)
    {
        $name = $button->getName();
        $vote = new Vote(); //todo editace :)
        $vote->setProposal($this->em->getReference('App\Model\Proposal', $this->getPresenter()->getParameter('id')));
        $vote->setAuthor($this->em->getReference('App\Model\User', $this->currentUser->getId()));
        $vote->setType($name == 'positive' ? true : false);
        $this->voteRepository->saveOrUpdate($vote);
        $this->onVote($this, $vote);
    }

    public function render()
    {
        $this->template->render(__DIR__ . '/VoteForm.latte');
    }

}

interface IVoteFormFactory
{
    /**
     * @param int $id
     * @return VoteForm
     */
    function create();
}