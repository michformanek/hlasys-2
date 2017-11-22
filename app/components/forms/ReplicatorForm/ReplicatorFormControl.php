<?php

namespace App\Forms;

use App\Model\Item;
use App\Model\Proposal;
use App\Model\User;
use App\Service\ItemService;
use App\Service\ProposalService;
use App\Service\VoteTypeService;
use App\Table\IItemTableFactory;
use Doctrine\ORM\EntityManager;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;

use Nette\Forms\Container;

/**
 * Class ReplicatorFormControl
 * @package App\Forms
 */
class ReplicatorFormControl extends Control
{

    private $description;

    /**
     * @var VoteTypeService
     */
    private $voteTypeService;
    /**
     * @var ItemService
     */
    private $itemService;
    /**
     * @var EntityManager
     */
    private $entityManager;
    /**
     * @var \Nette\Security\User
     */
    private $user;
    /**
     * @var ProposalService
     */
    private $proposalService;
    /**
     * @var IItemTableFactory
     */
    private $itemTableFactory;

    public function __construct(
        VoteTypeService $voteTypeService,
        ItemService $itemService,
        EntityManager $entityManager,
        IItemTableFactory $itemTableFactory,
        \Nette\Security\User $user,
        ProposalService $proposalService
    )
    {
        $this->description = '';
        $this->voteTypeService = $voteTypeService;
        $this->itemService = $itemService;
        $this->entityManager = $entityManager;
        $this->user = $user;
        $this->proposalService = $proposalService;
        $this->itemTableFactory = $itemTableFactory;
    }

    public function render()
    {
        $this->template->description = $this->description;
        $this->template->setFile(__DIR__ . '/ReplicatorFormControl.latte');
        $this->template->render();
    }

    protected function createComponentMyForm()
    {

        $form = new Form();

        $form->addHidden('id');

        $form->addText('title', 'Název')
            ->setRequired('Zadejte název žádosti');

        $form->addTextArea('description', 'Text hlasování')
            ->setRequired('Zadejte text žádosti');

        $form->addHidden('dateStart');
        $form->addHidden('dateEnd');

        $form->addSelect('voteType', 'Typ hlasování', $this->voteTypeService->getVoteTypeOptions())
            ->setRequired('Vyberte typ hlasování');

        $items = $form->addDynamic('items', function (Container $item) {
            $item->addText('amount', 'Mnozstvi');
            $item->addText('code', 'Kod');
            $item->addHidden('id');
            $item->addText('name', 'Nazev');
            $item->addText('price', 'Cena');
            $item->addText('url', 'Odkaz');
        }, 1);

        $items->addSubmit('add', 'Přidat položku')
            ->setValidationScope(FALSE)
            ->onClick[] = [$this, 'addItem'];

        $form->addSubmit('send', 'Odeslat')
            ->onClick[] = [$this, 'success'];

        return $form;
    }

    public function handleDescriptionChange($value)
    {
        $parsedown = new \Parsedown();
        $this->description = $parsedown->text($value);
        $this->redrawControl('markdown');
    }

    public function success($form, $values)
    {
        $proposal = new Proposal();
        $items = array();
        $userId = $this->user->getId();
        foreach ($values['items'] as $input) {
            if (empty($input['name'])) {
                continue;
            }
            $item = new Item();
            $item->setAmount($input['amount']);
            $item->setCode($input['code']);
            $item->setId($input['id']);
            $item->setName($input['name']);
            $item->setPrice($input['price']);
            $item->setUrl($input['url']);
            $item->setProposal($proposal);
            $items[] = $item;
        }
        $voteType = $this->voteTypeService->find($values['voteType']);
        $group = $voteType->getGroup();
        $proposal->setId($values['id']);
        $proposal->setTitle($values['title']);
        $proposal->setVoteType($voteType);
        $proposal->setDescription($values['description']);

        $dateStart = new \DateTime('now');
        $dateEnd = new \DateTime('now');
        $dateEnd->modify("+ 30 days");
        $proposal->setDateStart($dateStart);
        $proposal->setDateEnd($dateEnd);
        $proposal->setUser($this->entityManager->getReference(User::class, $userId));
        $proposal->setTrash(false);
        //fixme status
        if ($values['id'] != "") {
            $proposal->setDateStart($values['dateStart']);
            $proposal->setDateEnd($values['dateEnd']);
        }
        $proposal->setItems($items);
        $proposal->setGroup($group);

//        dump($proposal);
        //dump($values);
//        exit();
        $this->proposalService->save($proposal);
    }

    public function edit($proposalId)
    {
        $proposal = $this->proposalService->findOne($proposalId);
        $values = array();

        $values['id'] = $proposal->getId();
        $values['title'] = $proposal->getTitle();
        $values['description'] = $proposal->getDescription();
        $values['dateStart'] = $proposal->getDateStart()->format("j.n.Y H:i");
        $values['dateEnd'] = $proposal->getDateEnd()->format("j.n.Y H:i");
        $values['voteType'] = $proposal->getVoteType()->getId();
        $this['myForm']->setDefaults($values);
        $items = $proposal->getItems();
        $this['itemTable']->setItems($items);
        foreach ($items as $item) {
            $itemValue = array();
            $itemValue['amount'] = $item->getAmount();
            $itemValue['code'] = $item->getCode();
            $itemValue['id'] = $item->getId();
            $itemValue['name'] = $item->getName();
            $itemValue['price'] = $item->getPrice();
            $itemValue['url'] = $item->getUrl();
            $this['myForm']['items-'.$item->getId()]->setDefaults($itemValue);
        }

    }

    public function createComponentItemTable()
    {
        return $this->itemTableFactory->create([]);
    }

}

/**
 * Interface IReplicatorFormControl
 * @package App\Forms
 */
interface IReplicatorFormControl
{

    /**
     * @return ReplicatorFormControl
     */
    public function create();
}