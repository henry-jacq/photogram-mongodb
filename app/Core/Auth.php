<?php

namespace App\Core;

use Exception;
use App\Model\User;
use App\Core\Session;
use App\Interfaces\AuthInterface;

class Auth implements AuthInterface
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
        // Amount of cost requires to generate a random hash
        $options = [
            'cost' => 8
        ];

        $fullname = ucfirst(trim($credentials['fullname']));
        $password = password_hash($credentials['password'], PASSWORD_DEFAULT, $options);
        $email = $this->user->validateEmail($credentials['email']);
        $username = $this->user->validateUsername($credentials['username']);

        if ($email === false) {
            return false;
        }
        
        $data = [
            'fullname' => $fullname,
            'username' => $username,
            'email' => $email,
            'password' => $password
        ];

        try {
            if ($this->user->exists($data)) {
                return false;
            }
            
            return $this->user->create($data);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Login user
     */
    public function login(array $credentials)
    {
        $data = [
            'username' => $credentials['user'],
            'email' => $credentials['user']
        ];
        
        $result = $this->user->exists($data);
        
        if ($result !== false) {
            if (password_verify($credentials['password'], $result['password'])) {
                $this->session->put('user', (string) $result['_id']);
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