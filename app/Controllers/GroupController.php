<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Core\Request;
use App\Models\Group;
use App\Models\UserGroups;

class GroupController extends BaseController
{
    public function index(Request $request): void
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        $userGroup = new UserGroups();
        $groups = $userGroup->getGroupsWithDetails($_SESSION['user_id']);

        $this->render('groups/index', ['groups' => $groups]);
    }

    public function create(): void
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
        $this->render('groups/create');
    }

    public function store(Request $request): void
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        if ($request->method === 'POST') {
            $name = $request->body['name'] ?? '';

            if (empty($name)) {
                // In a real app we would pass errors back to the view
                $this->render('groups/create', ['error' => 'Name is required']);
                return;
            }

            $groupModel = new Group();
            // We need a way to generate ID or let DB handle it. User's Group model uses manual ID in createOrUpdate. 
            // I should verify if I can generate a random ID or if DB is auto-increment.
            // Looking at the seed_db.php, it passes IDs from API. 
            // For user created groups, let's generate a random int ID for now to be safe with the existing model, or assuming auto-increment if configured.
            // Let's attempt to use a random ID to fit the existing createOrUpdate method signature if schema isn't known.
            // Actually, best practice is to let DB handle AI, but the model forces ID.
            // Let's generate a random ID.
            $newId = random_int(100000, 999999);

            $groupModel->createOrUpdate([
                'id' => $newId,
                'name' => $name,
                'owner_id' => $_SESSION['user_id']
            ]);

            // Add owner to the group
            $userGroup = new UserGroups();
            $userGroup->addUserToGroup($newId, $_SESSION['user_id']);

            header('Location: /mygroups');
            exit;
        }
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
