<?php

namespace App\Core;

class Request
{
    public array $params = [];
    public array $body = [];
    public array $query = [];
    public string $method;
    public string $uri;

    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->uri = $_SERVER['REQUEST_URI'];
        $this->query = $_GET;

        // Handle JSON Input
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
        if (strpos($contentType, 'application/json') !== false) {
            $json = file_get_contents('php://input');
            $this->body = json_decode($json, true) ?? [];
        } else {
            $this->body = $_POST;
        }
    }
}