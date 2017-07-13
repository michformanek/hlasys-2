<?php

namespace App\Forms;

use App\Model\Comment;
use App\Repository\CommentRepository;
use App\Repository\GroupRepository;
use App\Repository\ProposalRepository;
use App\Repository\UserRepository;
use DateTime;
use Nette\Application\UI;
use Nette\Security\User;

class FilterForm extends UI\Control
{
    public $onFilter;
    /**
     * @var GroupRepository
     */
    private $groupRepository;
    /**
     * @var
     */
    private $defaults;
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * FilterForm constructor.
     */
    public function __construct(GroupRepository $groupRepository,UserRepository $userRepository,$defaults)
    {
        parent::__construct();
        $this->groupRepository = $groupRepository;
        $this->defaults = $defaults;
        $this->userRepository = $userRepository;
    }

    /**
     * @return Form
     */
    protected function createComponentFilterForm()
    {
        $groups = $this->groupRepository->findAll();
        $users = $this->userRepository->findAll();

        $form = new UI\Form;
        $form->addSelect('group', 'Bude rozhodovat:', $this->convertGroupsToArray($groups));
        $form->addSelect('user', 'Autor:', $this->convertUsersToArray($users));
        $form->addCheckbox('trash', 'Zobrazit smazané');
        $form->addSubmit('send', 'Filtrovat');
        $form->onSuccess[] = [$this, 'filterFormSucceeded'];
        $form->setDefaults($this->defaults);
        return $form;
    }

    public function filterFormSucceeded($form,array $values)
    {
        $this->onFilter($form, $values);
    }

    public function render()
    {
        $this->template->render(__DIR__ . '/FilterForm.latte');
    }

    private function convertGroupsToArray($groups)
    {
        $result = array();
        foreach ($groups as $group) {
            $result[$group->getId()] = $group->getName();
        }
        return $result;
    }

    private function convertUsersToArray($users)
    {
        $result = array();
        foreach ($users as $user) {
            $result[$user->getId()] = $user->getFullName();
        }
        return $result;
    }
}

interface IFilterFormFactory
{
    /**
     * @return FilterForm
     */
    function create($defaults);
}