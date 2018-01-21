<?php

namespace App\Service;

use App\Model\Item;
use Kdyby\Doctrine\EntityManager;

class ItemService
{
    private $entityManager;
    private $itemRepository;

    /**
     * ItemService constructor.
     * @param EntityManager $entityManager
     * @internal param ItemRepository $itemRepository
     */
    public function __construct(
        EntityManager $entityManager
    )
    {
        $this->entityManager= $entityManager;
        $this->itemRepository = $entityManager->getRepository(Item::class);
    }

    public function findAll()
    {
        return $this->itemRepository->findAll();
    }
}