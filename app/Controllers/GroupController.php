
<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Core\Request;
use App\Models\Group;
use App\Models\UserGroups;

class GroupController extends BaseController
{
    public function createGroup(Request $request): void
    {
        $group = new Group();
        $group->createOrUpdate([
            'id' => $request->post('id'),
            'name' => $request->post('name'),
            'owner_id' => $request->post('owner_id')
        ]);
    }

    public function addUserToGroup(Request $request): void
    {
        $userGroup = new UserGroups();
        $userGroup->addUserToGroup($request->post('group_id'), $request->post('user_id'));
    }

    public function removeUserFromGroup(Request $request): void
    {
        $userGroup = new UserGroups();
        $userGroup->removeUserFromGroup($request->post('group_id'), $request->post('user_id'));
    }

    public function getUserGroups(Request $request): void
    {
        $userGroup = new UserGroups();
        $userGroup->getUserGroups($request->post('user_id'));
    }

    public function getGroupUsers(Request $request): void
    {
        $userGroup = new UserGroups();
        $userGroup->getGroupUsers($request->post('group_id'));
    }
}
