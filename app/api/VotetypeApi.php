<?php

namespace App\Api;


use App\Service\VoteTypeService;
use Drahak\Restful\Application\UI\SecuredResourcePresenter;
use Drahak\Restful\IResource;

class VotetypeApi extends SecuredResourcePresenter
{
    /**
     * @var VoteTypeService
     */
    private $voteTypeService;


    /**
     * VoteTypeApi constructor.
     * @param VoteTypeService $voteTypeService
     */
    public function __construct(VoteTypeService $voteTypeService)
    {
        $this->voteTypeService = $voteTypeService;
    }

    public function actionRead($id)
    {
        if (isset($id)) {
            $voteType = $this->voteTypeService->find($id);
            $this->resource = json_decode(json_encode($voteType), true);
        } else {
            $voteTypes = $this->voteTypeService->findAll();
            $this->resource = json_decode(json_encode($voteTypes), true);;
        }
        $this->sendResource(IResource::JSON);
    }

    public function actionDelete($id)
    {
        $voteType = $this->voteTypeService->delete($id);
        $this->resource = json_decode(json_encode($voteType), true);
        $this->sendResource(IResource::JSON);
    }

    public function actionCreate()
    {
        $voteType = $this->voteTypeService->create($this->getInput()->getData());
        $this->resource = json_decode(json_encode($voteType), true);
        $this->sendResource(IResource::JSON);
    }
}