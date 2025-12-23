<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Core\Request;
use App\Models\User;

class AuthController extends BaseController
{
    public function login(Request $request): void
    {
        if ($request->method === 'POST') {
            $email = $request->body['email'] ?? '';
            $password = $request->body['password'] ?? '';

            $userModel = new User();
            $user = $userModel->findByEmail($email);

            if ($user && password_verify($password, $user->password)) {
                $_SESSION['user_id'] = $user->id;
                $_SESSION['user_name'] = $user->name;
                header('Location: /groups');
                exit;
            }

            $this->render('auth/login', ['error' => 'Invalid credentials']);
            return;
        }

        $this->render('auth/login');
    }

    public function register(Request $request): void
    {
        if ($request->method === 'POST') {
            $name = $request->body['name'] ?? '';
            $email = $request->body['email'] ?? '';
            $password = $request->body['password'] ?? '';

            $userModel = new User();
            if ($userModel->findByEmail($email)) {
                $this->render('auth/register', ['error' => 'Email already exists']);
            }

            if ($userModel->create($name, $email, $password)) {
                // Auto-login
                $user = $userModel->findByEmail($email);
                $_SESSION['user_id'] = $user->id;
                $_SESSION['user_name'] = $user->name;

                header('Location: /');
                exit;
            }
        }

        $this->render('auth/register');
    }

    public function logout()
    {
        session_destroy();
        header('Location: /login');
        exit;
    }
}
