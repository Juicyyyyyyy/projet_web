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
        $this->body = $_POST;
        $this->query = $_GET;
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->uri = $_SERVER['REQUEST_URI'];
    }
}