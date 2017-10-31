<?php

namespace App\Table;


use Nette\Application\UI\Control;

class ItemTable extends Control
{
    /** @persistent */
    public $items;

    public function __construct($items)
    {
        $this->items = $items;
    }

    public function render()
    {
        $template = $this->template;
        $template->setFile(__DIR__ . '/ItemTable.latte');
        $template->items = $this->items;
        $template->render();
    }


    public function getItems()
    {
        return $this->items;
    }


    public function setItems($items)
    {
        $this->items = $items;
    }
}

interface IItemTableFactory
{
    /**
     * @return ItemTable
     */
    public function create($items);
}
