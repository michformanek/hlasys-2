<?php

namespace App\Api;

use App\Service\GroupService;
use Drahak\Restful\Application\UI\ResourcePresenter;
use Drahak\Restful\Application\UI\SecuredResourcePresenter;
use Drahak\Restful\IResource;

class GroupApi extends SecuredResourcePresenter
{
    /**
     * @var GroupService
     */
    private $groupService;


    /**
     * GroupApi constructor.
     * @param GroupService $groupService
     */
    public function __construct(GroupService $groupService)
    {

        $this->groupService = $groupService;
    }

    public function actionRead($id)
    {
        if (isset($id)) {
            $group = $this->groupService->findOne($id);
            $this->resource = Convertor::convertGroup($group);
        } else {
            $groups = $this->groupService->findAll();
            $this->resource = Convertor::convertGroups($groups);
        }
        $this->sendResource(IResource::JSON);
    }
}