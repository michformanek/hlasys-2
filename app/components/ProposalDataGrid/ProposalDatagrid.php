<?php
namespace App\Components;

use App\Repository\ProposalRepository;
use Ublaboo\DataGrid\DataGrid;
use Ublaboo\DataGrid\Localization\SimpleTranslator;
use Dibi;

/**
 * Created by PhpStorm.
 * User: mformanek
 * Date: 6.2.17
 * Time: 11:42
 */
class ProposalDatagrid extends \Nette\Application\UI\Control
{

    /**
     * @var ProposalRepository
     */
    public $proposalRepository;

    private $onDetail;

    public function __construct(callable $onDetail,ProposalRepository $proposalRepository)
    {
        $this->proposalRepository = $proposalRepository;
        $this->onDetail = $onDetail;
    }


    public function render()
    {
        $this->template->render(__DIR__ . '/ProposalGrid.latte');
    }


    public function createComponentSimpleGrid($name)
    {
        $grid = new DataGrid($this, $name);

        $grid->setDataSource($this->proposalRepository->createQueryBuilder('pr'));
        $grid->addColumnText('title', 'Title')
            ->setSortable();
        $grid->addColumnText('description', 'Popis')
            ->setSortable();
        //$grid->addColumnText('', 'Cena');
        $grid->addColumnText('author.fullname', 'Autor');
        $grid->addColumnDateTime('dateCreated', 'Datum vložení')
            ->setSortable();
        $grid->addColumnDateTime('dateEnd', 'Konec hlasování')
            ->setSortable();
        //$grid->addColumnText('', 'Progress');
        $grid->addColumnText('status.name', 'Stav')
            ->setSortable();
        $grid->addAction('delete', '', 'delete!')
            ->setIcon('trash')
            ->setTitle('Delete')
            ->setClass('btn btn-xs btn-danger ajax')
            ->setConfirm('Do you really want to delete example %s?', 'title');
        $grid->addAction('detail', '', 'detail!')
            ->setIcon('trash')
            ->setTitle('Open')
            ->setClass('btn btn-xs btn-info ajax');
    }

    public function handleDelete($id)
    {
        $proposal = $this->proposalRepository->findOneBy(array('id' => $id));
        $proposal->setTrash(true);
        $this->proposalRepository->saveOrUpdate($proposal);

        $this->flashMessage("Item deleted [$id]", 'info');

        if ($this->presenter->isAjax()) {
            $this->redrawControl('flashes');
            $this['simpleGrid']->reload();
        } else {
            $this->redirect('this');
        }
    }

    public function handleDetail($id)
    {
        $this->onDetail($id);
    }


}

interface IProposalDatagridFactory
{
    /**
     * @param callable $onDetail
     * @return ProposalDatagrid
     */
    function create(callable $onDetail);
}