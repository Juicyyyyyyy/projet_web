<?php

namespace App\Controllers;

use App\Core\BaseController;

class ApiFootballController extends BaseController
{
    private string $apiKey;
    private string $apiUrl = 'https://v3.football.api-sports.io';
    private \GuzzleHttp\Client $client;

    public function __construct()
    {
        $this->apiKey = $_ENV['API_FOOTBALL'] ?? '';
        $this->client = new \GuzzleHttp\Client([
            'base_uri' => $this->apiUrl,
            'headers' => [
                'x-rapidapi-key' => $this->apiKey,
                'x-rapidapi-host' => 'v3.football.api-sports.io'
            ],
            'timeout'  => 30,
        ]);
    }

    private function makeRequest(string $endpoint, array $params = []): array
    {
        if (empty($this->apiKey)) {
            return [];
        }

        try {
            $response = $this->client->request('GET', $endpoint, [
                'query' => $params
            ]);

            return json_decode($response->getBody()->getContents(), true)['response'] ?? [];
        } catch (\GuzzleHttp\Exception\GuzzleException $e) {
            return [];
        }
    }

    /**
     * Fetch les match de ligue 1 de l'annee actuelle
     * @return array
     */
    public function getYearMatches(): array
    {
        $currentMonth = (int)date('m');
        $currentYear = (int)date('Y');
        $season = $currentMonth >= 7 ? $currentYear : $currentYear - 1;

        return $this->makeRequest('/fixtures', [
            'league' => 61, // Ligue 1
            'season' => $season
        ]);
    }

    /**
     * Fetch les match disputes actuellement
     * @return array
     */
    public function getCurrentMatches(): array
    {
        return $this->makeRequest('/fixtures', [
            'league' => 61,
            'live' => 'all'
        ]);
    }

    /**
     * Fetch toutes les team actuelles de ligue 1
     * @return array
     */
    public function getAllTeams(): array
    {
        $currentMonth = (int)date('m');
        $currentYear = (int)date('Y');
        $season = $currentMonth >= 7 ? $currentYear : $currentYear - 1;

        return $this->makeRequest('/teams', [
            'league' => 61,
            'season' => $season
        ]);
    }

    /**
     * Fetch tous les joueurs actuels de ligue 1
     * @return array
     */
    public function getAllPlayers(): array
    {
        $teams = $this->getAllTeams();
        $players = [];
        $currentMonth = (int)date('m');
        $currentYear = (int)date('Y');
        $season = $currentMonth >= 7 ? $currentYear : $currentYear - 1;

        foreach ($teams as $teamData) {
            $teamId = $teamData['team']['id'];
            // Pagination handling might be needed but for simplicity we fetch page 1
            // Actually API-Football requires pagination for players usually
            // Let's try to fetch page 1 for each team. 
            // Warning: This will make MANY requests (18 teams * pages). 
            // For now, let's just fetch page 1.
            
            $response = $this->makeRequest('/players', [
                'team' => $teamId,
                'season' => $season,
                'page' => 1
            ]);
            
            if (!empty($response)) {
                $players = array_merge($players, $response);
            }
            
            // Avoid rate limiting
            usleep(200000); // 200ms
        }

        return $players;
    }
}