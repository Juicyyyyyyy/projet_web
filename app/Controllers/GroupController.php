<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Core\Request;
use App\Models\Group;
use App\Models\UserGroups;
use App\Models\GroupMatch;

class GroupController extends BaseController
{
    public function createGroup(Request $request): void
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

    public function getMyGroups(Request $request): void
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

    public function getGroup(Request $request): void
    {
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Non authentifie']);
            return;
        }

        $groupId = (int)($request->params['id'] ?? 0);

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

        $groupId = (int)($request->params['id'] ?? 0);
        $userId = (int)($request->body['user_id'] ?? 0);

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

        $groupId = (int)($request->params['id'] ?? 0);
        $userId = (int)($request->body['user_id'] ?? 0);

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

        $groupId = (int)($request->params['id'] ?? 0);
        $matchId = (int)($request->body['match_id'] ?? 0);

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

        $groupId = (int)($request->params['id'] ?? 0);
        $matchId = (int)($request->body['match_id'] ?? 0);

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

        $groupId = (int)($request->params['id'] ?? 0);

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
