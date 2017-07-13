<?php

namespace App\Forms;

use App\Model\Comment;
use App\Model\Proposal;
use App\Repository\CommentRepository;
use App\Repository\GroupRepository;
use App\Repository\ProposalRepository;
use App\Repository\UserRepository;
use DateTime;
use Nette\Application\UI;
use Nette\Security\User;

class ProposalForm extends UI\Control
{

    public $onProposalSave;
    /**
     * @var User
     */
    private $currentUser;
    /**
     * @var ProposalRepository
     */
    private $proposalRepository;
    /**
     * @var GroupRepository
     */
    private $groupRepository;
    private $userRepository;

    public function __construct(User $currentUser,
                                ProposalRepository $proposalRepository,
                                UserRepository $userRepository,
                                GroupRepository $groupRepository)
    {
        parent::__construct();
        $this->currentUser = $currentUser;
        $this->proposalRepository = $proposalRepository;
        $this->groupRepository = $groupRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * @return Form
     */
    protected function createComponentProposalForm()
    {
        $groups = $this->groupRepository->findAll();

        $form = new UI\Form;
        $form->addText('title', 'Titulek:')
            ->setRequired('Prosím zadejte název žádosti');
        $form->addTextArea('description');
        $form->addSelect('group', 'Bude rozhodovat:', $this->convertGroupsToArray($groups));

//        $form->addText('dateStart', 'Datum začátku hlasování')
//            ->setType('date')
//            ->setDefaultValue('2017-06-0'); //FIXME DateTime(now), vlastni metoda na set defauts, ktera bude vyhledavat v repository jestli existuje navrh k editaci podle id :)

        $form->addSubmit('send', 'Vytvořit návrh');
        $form->onSuccess[] = [$this, 'proposalFormSucceeded'];
        return $form;

    }

    //FIXME: čas vytvoření, autor příspěvku, editace
    public function proposalFormSucceeded($form, $values)
    {

//        dump($values);
//        exit();
        $this->currentUser->login('1', '1234');


        $proposal = new Proposal();

        $userId = $this->currentUser->id ? $this->currentUser->id : 1; //FIXME: Pouze přihlášené!!!
        $proposal->setAuthor($this->userRepository->find($userId));

        $dateStart = new \DateTime('now');
        $dateEnd = new \DateTime('now');
        $dateEnd->modify("+ 30 days");
        $proposal->setDateCreated($dateStart);
        $proposal->setDateEnd($dateEnd);

        $proposal->setDescription($values->description);
        $proposal->setResponsibleGroup($this->groupRepository->find($values->group));
       // $proposal->setStatus();
        $proposal->setTitle($values->title);
        $proposal->setTrash(false);

        $this->proposalRepository->saveOrUpdate($proposal);
        $this->flashMessage('Návrh byl uložen', 'success');
        $this->onProposalSave($this, $proposal);
    }

    private function convertGroupsToArray($groups)
    {
        $result = array();
        foreach ($groups as $group) {
            $result[$group->getId()] = $group->getName();
        }
        return $result;
    }

    public function render()
    {
        $this->template->render(__DIR__ . '/ProposalForm.latte');
    }
}

interface IProposalFormFactory
{
    /**
     * @param int $id
     * @return ProposalForm
     */
    function create();
}