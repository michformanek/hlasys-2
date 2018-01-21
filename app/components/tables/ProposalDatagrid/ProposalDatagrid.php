<?php

namespace App\Table;


use App\Model\Proposal;
use App\Service\UserService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use Nette\Application\UI\Control;
use Ublaboo\DataGrid\DataGrid;

class ProposalDatagrid extends Control
{

    /**
     * @var EntityManager
     */
    private $entityManager;
    private $userService;
    /**
     * @var QueryBuilder
     */
    private $datasource;

    public function __construct(EntityManager $entityManager, UserService $userService, QueryBuilder $datasource)
    {
        $this->entityManager = $entityManager;
        $this->userService = $userService;
        $this->datasource = $datasource;
    }

    public function createComponentGrid($name)
    {
        $grid = new DataGrid($this, $name);

        $grid->setDataSource($this->datasource);
        $grid->addColumnText('id', 'Id');
        $grid->addColumnLink('title', 'Název', 'Proposal:detail')->setSortable();
        $grid->addColumnText('price', 'Cena')
            ->setRenderer(function (Proposal $proposal) {
                $sum = null;
                foreach ($proposal->getItems() as $item) {
                    $sum += $item->getPrice();
                }
                return $sum;
            });

        $grid->addColumnText('results', 'Pro/Proti/Nehlasovalo')
            ->setRenderer(function (Proposal $proposal) {
                $voteResults = $proposal->getVoteResult();
                if ($voteResults != null) {
                    return $voteResults->getPositiveVotes() . ' / ' . $voteResults->getNegativeVotes() . ' / ' . $voteResults->getUnvoted();
                }
                return null;
            });


        $grid->addColumnText('status.name', 'Stav');
        $grid->addColumnText('group.name', 'Rozhoduje');
        $grid->addColumnText('user.username', 'Vytvořil');
        $grid->addColumnDateTime('dateStart', 'Od');
        $grid->addColumnDateTime('dateEnd', 'Do')->setRenderer(
            function ($item) {
                return ' - ';
            }, function ($item) {
            return (bool)($item->getDateEnd() != null);
        });

        $grid->setRowCallback(function (Proposal $proposal, $tr) {
            $voteResult = $proposal->getVoteResult();
            if ($voteResult->didPass()) {
                $tr->addClass('table-success text-white');
            } else if ($voteResult->someoneVoted() && !$voteResult->didPass()) {
                $tr->addClass('table-danger text-white');
            } else if (!$proposal->didUserVote($this->userService->getUserReference())) {
                $tr->addClass('table-active');
            }
        });

        $grid->setItemsDetail(__DIR__ . '/ItemDetail.latte');
        $grid->setTemplateFile(__DIR__ . '/CustomGrid.latte');

    }


    public function render()
    {
        $template = $this->template;
        $template->setFile(__DIR__ . '/ProposalDatagrid.latte');
        $template->render();
    }

}

interface IProposalDatagridFactory
{
    /**
     * @param QueryBuilder $datasource
     * @return ProposalDatagrid
     */
    public function create(QueryBuilder $datasource);
}
