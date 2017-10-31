<?php

namespace App\Service;


use App\Repository\ItemRepository;

class ItemService
{
    /**
     * @var ItemRepository
     */
    private $itemRepository;


    /**
     * ItemService constructor.
     * @param ItemRepository $itemRepository
     */
    public function __construct(ItemRepository $itemRepository)
    {
        $this->itemRepository = $itemRepository;
    }

    public function findAll()
    {
        return $this->itemRepository->findAll();
    }
}