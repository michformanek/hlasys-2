<?php

namespace App\Forms;


use App\Service\WatchService;
use Nette\Application\UI\Control;
use Tracy\Debugger;

class WatchForm extends Control
{

    /**
     * @var
     */
    private $proposalId;
    /**
     * @var WatchService
     */
    private $watchService;

    public $onWatchChanged;

    public function __construct(
        $proposalId,
        WatchService $watchService
    )
    {
        $this->proposalId = $proposalId;
        $this->watchService = $watchService;
    }

    public function render()
    {
        $currentWatch = $this->watchService->getWatchOfCurrentUser($this->proposalId);
        $template = $this->template;
        $template->setFile(__DIR__ . '/WatchForm.latte');
        $template->watch = $currentWatch;
        $template->render();
    }

    public function handleAdd()
    {
        $this->watchService->addWatch($this->proposalId);
        $this->onWatchChanged($this->proposalId);
    }

    public function handleRemove($watchId)
    {
        $this->watchService->removeWatch($watchId);
        $this->onWatchChanged($this->proposalId);
    }
}

interface IWatchFormFactory
{
    /**
     * @return WatchForm
     */
    public function create($proposalId);
}
