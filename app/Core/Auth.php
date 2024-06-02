<?php

namespace App\Core;

use Exception;
use App\Model\User;
use App\Core\Session;

class Auth
{ 
    public function __construct(
        private readonly User $user,
        private readonly Session $session,
    )
    {
    }

    /**
     * Register user
     */
    public function register(array $credentials)
    {
        $email = $this->user->validateEmail($credentials['email']);
        $username = $this->user->validateUsername($credentials['username']);

        if ($username === 'admin') {
            return false;
        }
        
        if ($email === false) {
            return false;
        }

        try {
            if ($this->user->exists($username)) {
                return false;
            }
            
            return $this->user->create($credentials);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Login user with username or email
     */
    public function login($user, $password)
    {
        $result = $this->user->exists($user);
        
        if ($result !== false) {
            if (password_verify($password, $result['password'])) {
                $this->session->put('user', $result['id']);
                return true;
            }
        }
        return false;
    }
    
    /**
     * Logout user from the session
     */
    public function logout(): void
    {
        $this->session->forget('user');
    }

    /**
     * Check if the user is logged in or not
     */
    public function isAuthenticated(): bool
    {
        if ($this->session->get('user') !== null) {
            return true;
        }
        return false;
    }
}