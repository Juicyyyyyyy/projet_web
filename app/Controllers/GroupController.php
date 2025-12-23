<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Core\Request;
use App\Models\Group;
use App\Models\UserGroups;
use App\Models\GroupMatch;

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

    public function show(Request $request): void
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        $groupId = (int) ($request->params['id'] ?? 0);
        $groupModel = new Group();

        if (!$groupModel->isMember($groupId, $_SESSION['user_id'])) {
            header('Location: /groups');
            exit;
        }

        $group = $groupModel->findWithDetails($groupId);
        $userGroups = new UserGroups();
        $members = $userGroups->getGroupUsers($groupId);

        $this->render('groups/show', ['group' => $group, 'members' => $members]);
    }

    public function createApi(Request $request): void
    {
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Non authentifie']);
            return;
        }

        if ($request->method !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Methode non autorisee']);
            return;
        }

        $name = $request->body['name'] ?? '';
        if (empty($name)) {
            http_response_code(400);
            echo json_encode(['error' => 'Nom du groupe requis']);
            return;
        }

        $group = new Group();
        $groupId = $group->create($name, $_SESSION['user_id']);

        if ($groupId) {
            http_response_code(201);
            echo json_encode(['success' => true, 'group_id' => $groupId]);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Erreur lors de la creation']);
        }
    }

    public function listApi(Request $request): void
    {
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Non authentifie']);
            return;
        }

        $group = new Group();
        $groups = $group->findByUser($_SESSION['user_id']);

        http_response_code(200);
        echo json_encode(['success' => true, 'groups' => $groups]);
    }

    public function getGroupApi(Request $request): void
    {
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Non authentifie']);
            return;
        }

        $groupId = (int) ($request->params['id'] ?? 0);

        $group = new Group();
        if (!$group->isMember($groupId, $_SESSION['user_id'])) {
            http_response_code(403);
            echo json_encode(['error' => 'Acces refuse']);
            return;
        }

        $groupData = $group->findWithDetails($groupId);
        $userGroups = new UserGroups();
        $members = $userGroups->getGroupUsers($groupId);

        http_response_code(200);
        echo json_encode(['success' => true, 'group' => $groupData, 'members' => $members]);
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

                $this->render('groups/create', ['error' => 'Name is required']);
                return;
            }

            $groupModel = new Group();

            $newId = random_int(100000, 999999);

            $groupModel->createOrUpdate([
                'id' => $newId,
                'name' => $name,
                'owner_id' => $_SESSION['user_id']
            ]);

            $userGroup = new UserGroups();
            $userGroup->addUserToGroup($newId, $_SESSION['user_id']);

            header('Location: /groups');
            exit;
        }
    }


    public function addUserToGroup(Request $request): void
    {
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Non authentifie']);
            return;
        }

        if ($request->method !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Methode non autorisee']);
            return;
        }

        $groupId = (int) ($request->params['id'] ?? 0);
        $userId = (int) ($request->body['user_id'] ?? 0);

        $group = new Group();
        if (!$group->isOwner($groupId, $_SESSION['user_id'])) {
            http_response_code(403);
            echo json_encode(['error' => 'Seul l\'admin peut ajouter des membres']);
            return;
        }

        $userGroup = new UserGroups();
        $result = $userGroup->addUserToGroup($groupId, $userId);

        if ($result) {
            http_response_code(200);
            echo json_encode(['success' => true, 'message' => 'Membre ajoute']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Erreur lors de l\'ajout']);
        }
    }

    public function removeUserFromGroup(Request $request): void
    {
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Non authentifie']);
            return;
        }

        if ($request->method !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Methode non autorisee']);
            return;
        }

        $groupId = (int) ($request->params['id'] ?? 0);
        $userId = (int) ($request->body['user_id'] ?? 0);

        $group = new Group();
        if (!$group->isOwner($groupId, $_SESSION['user_id'])) {
            http_response_code(403);
            echo json_encode(['error' => 'Seul l\'admin peut supprimer des membres']);
            return;
        }

        $userGroup = new UserGroups();
        $result = $userGroup->removeUserFromGroup($groupId, $userId);

        if ($result) {
            http_response_code(200);
            echo json_encode(['success' => true, 'message' => 'Membre supprime']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Erreur lors de la suppression']);
        }
    }

    public function addMatchToGroup(Request $request): void
    {
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Non authentifie']);
            return;
        }

        if ($request->method !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Methode non autorisee']);
            return;
        }

        $groupId = (int) ($request->params['id'] ?? 0);
        $matchId = (int) ($request->body['match_id'] ?? 0);

        $group = new Group();
        if (!$group->isOwner($groupId, $_SESSION['user_id'])) {
            http_response_code(403);
            echo json_encode(['error' => 'Seul l\'admin peut ajouter des matchs']);
            return;
        }

        $groupMatch = new GroupMatch();
        $result = $groupMatch->addMatchToGroup($groupId, $matchId);

        if ($result) {
            http_response_code(200);
            echo json_encode(['success' => true, 'message' => 'Match ajoute au groupe']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Erreur lors de l\'ajout du match']);
        }
    }

    public function removeMatchFromGroup(Request $request): void
    {
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Non authentifie']);
            return;
        }

        if ($request->method !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Methode non autorisee']);
            return;
        }

        $groupId = (int) ($request->params['id'] ?? 0);
        $matchId = (int) ($request->body['match_id'] ?? 0);

        $group = new Group();
        if (!$group->isOwner($groupId, $_SESSION['user_id'])) {
            http_response_code(403);
            echo json_encode(['error' => 'Seul l\'admin peut supprimer des matchs']);
            return;
        }

        $groupMatch = new GroupMatch();
        $result = $groupMatch->removeMatchFromGroup($groupId, $matchId);

        if ($result) {
            http_response_code(200);
            echo json_encode(['success' => true, 'message' => 'Match supprime du groupe']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Erreur lors de la suppression']);
        }
    }

    public function getGroupMatches(Request $request): void
    {
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Non authentifie']);
            return;
        }

        $groupId = (int) ($request->params['id'] ?? 0);

        $group = new Group();
        if (!$group->isMember($groupId, $_SESSION['user_id'])) {
            http_response_code(403);
            echo json_encode(['error' => 'Acces refuse']);
            return;
        }

        $groupMatch = new GroupMatch();
        $matches = $groupMatch->getMatchesByGroup($groupId);

        http_response_code(200);
        echo json_encode(['success' => true, 'matches' => $matches]);
    }
}
