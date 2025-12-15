<?php

require_once __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;
use App\Controllers\ApiFootballController;
use App\Models\Team;
use App\Models\Player;
use App\Models\Match as FootballMatch;
use App\Models\User;
use App\Models\Group;
use App\Models\UserGroups;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

echo "Starting seeder...\n";

$apiController = new ApiFootballController();
$teamModel = new Team();
$playerModel = new Player();
$matchModel = new FootballMatch();
$userModel = new User();
$groupModel = new Group();
$userGroupsModel = new UserGroups();

// Check for new matches
$lastMatchDate = $matchModel->getLastMatchDate();
echo "Last match date in DB: " . ($lastMatchDate ?? 'None') . "\n";

$matches = $apiController->getYearMatches();

if (empty($matches)) {
    echo "No matches found from API.\n";
    exit;
}

// Sort matches by date to find the latest one from API
usort($matches, function ($a, $b) {
    return strtotime($b['fixture']['date']) - strtotime($a['fixture']['date']);
});

$latestMatchDateApi = $matches[0]['fixture']['date'];
echo "Latest match date in API: " . $latestMatchDateApi . "\n";

if ($lastMatchDate && strtotime($latestMatchDateApi) <= strtotime($lastMatchDate)) {
    echo "No new matches to seed.\n";
    exit;
}

echo "New matches found. Starting seed process...\n";

// 1. Seed Teams
echo "Fetching Teams...\n";
$teams = $apiController->getAllTeams();
foreach ($teams as $teamData) {
    $team = $teamData['team'];
    $venue = $teamData['venue'];
    
    echo "Saving Team: " . $team['name'] . "\n";
    $teamModel->createOrUpdate([
        'id' => $team['id'],
        'name' => $team['name'],
        'logo' => $team['logo'],
        'venue_name' => $venue['name'],
        'city' => $venue['city']
    ]);
}

// 2. Seed Matches
echo "Saving Matches...\n";
foreach ($matches as $matchData) {
    $fixture = $matchData['fixture'];
    $teams = $matchData['teams'];
    $goals = $matchData['goals'];
    $league = $matchData['league'];
    
    $matchModel->createOrUpdate([
        'id' => $fixture['id'],
        'date' => date('Y-m-d H:i:s', strtotime($fixture['date'])),
        'status' => $fixture['status']['short'],
        'home_team_id' => $teams['home']['id'],
        'away_team_id' => $teams['away']['id'],
        'home_score' => $goals['home'],
        'away_score' => $goals['away'],
        'league_id' => $league['id'],
        'season' => $league['season']
    ]);
}

// 3. Seed Players
echo "Fetching Players (this may take a while)...\n";
$players = $apiController->getAllPlayers();
foreach ($players as $playerData) {
    $player = $playerData['player'];
    $statistics = $playerData['statistics'][0]; // Assuming first statistic is relevant for team
    
    echo "Saving Player: " . $player['name'] . "\n";
    $playerModel->createOrUpdate([
        'id' => $player['id'],
        'name' => $player['name'],
        'firstname' => $player['firstname'],
        'lastname' => $player['lastname'],
        'age' => $player['age'],
        'nationality' => $player['nationality'],
        'height' => $player['height'],
        'weight' => $player['weight'],
        'photo' => $player['photo'],
        'team_id' => $statistics['team']['id']
    ]);
}

// 4. Seed Users
echo "Seeding Users...\n";
$usersToSeed = [
    ['name' => 'Admin User', 'email' => 'admin@example.com', 'password' => 'password123'],
    ['name' => 'John Doe', 'email' => 'john@example.com', 'password' => 'password123'],
    ['name' => 'Jane Doe', 'email' => 'jane@example.com', 'password' => 'password123'],
];

foreach ($usersToSeed as $userData) {
    if (!$userModel->findByEmail($userData['email'])) {
        echo "Creating User: " . $userData['name'] . "\n";
        $userModel->create($userData['name'], $userData['email'], $userData['password']);
    } else {
        echo "User already exists: " . $userData['name'] . "\n";
    }
}

// 5. Seed Groups
echo "Seeding Groups...\n";
$adminUser = $userModel->findByEmail('admin@example.com');
$johnUser = $userModel->findByEmail('john@example.com');

if ($adminUser && $johnUser) {
    $groupsToSeed = [
        ['id' => 1, 'name' => 'General Group', 'owner_id' => $adminUser->id],
        ['id' => 2, 'name' => 'Football Fans', 'owner_id' => $johnUser->id],
    ];

    foreach ($groupsToSeed as $groupData) {
        echo "Saving Group: " . $groupData['name'] . "\n";
        $groupModel->createOrUpdate($groupData);
    }

    // 6. Seed UserGroups
    echo "Seeding UserGroups...\n";
    $janeUser = $userModel->findByEmail('jane@example.com');
    
    $memberships = [
        ['group_id' => 1, 'user_id' => $johnUser->id],
        ['group_id' => 1, 'user_id' => $janeUser->id],
        ['group_id' => 2, 'user_id' => $johnUser->id],
    ];

    foreach ($memberships as $membership) {
        // Check if user is already in group
        $existingUsers = $userGroupsModel->getGroupUsers($membership['group_id']);
        $alreadyMember = false;
        foreach ($existingUsers as $member) {
            if ($member->user_id == $membership['user_id']) {
                $alreadyMember = true;
                break;
            }
        }
        
        if (!$alreadyMember) {
            echo "Adding User " . $membership['user_id'] . " to Group " . $membership['group_id'] . "\n";
            $userGroupsModel->addUserToGroup($membership['group_id'], $membership['user_id']);
        }
    }
}

echo "Seeding completed.\n";
