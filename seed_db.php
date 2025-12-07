<?php

require_once __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;
use App\Controllers\ApiFootballController;
use App\Models\Team;
use App\Models\Player;
use App\Models\MatchModel;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

echo "Starting seeder...\n";

$apiController = new ApiFootballController();
$teamModel = new Team();
$playerModel = new Player();
$matchModel = new MatchModel();

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

echo "Seeding completed.\n";
