<?php

namespace App\Core;

abstract class BaseController
{
    protected string $viewPath = __DIR__ . '/../Views/';
    
    public function render(string $pageName, array $data = []): void
    {
        extract($data);
        
        $pagePath = $this->viewPath . $pageName . '.php';
        
        if (!file_exists($pagePath))
        {
            die("Error: page not found at " . $pagePath);
        }
        
        ob_start();
        
        include $pagePath;
        
        echo ob_get_clean();
    }
}