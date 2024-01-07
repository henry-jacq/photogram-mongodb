<?php

namespace App\Core;

use Exception;
// use App\Model\User;
use App\Core\Session;
use App\Interfaces\AuthInterface;
// use App\Services\HashService;

class Auth implements AuthInterface
{ 
    public function __construct(
        // private readonly User $user,
        private readonly Session $session,
        // private readonly HashService $hasher,
    )
    {
    }

    public function user()
    {
        
    }
    
    /**
     * Register user
     */
    public function register(array $credentials)
    {      
        $fullname = ucfirst(trim($credentials['fullname']));
        $password = $this->hasher->make($credentials['password']);
        $email = $this->user->validateEmail($credentials['email_address']);
        $username = $this->user->validateUsername($credentials['username']);

        if ($email === false) {
            return false;
        }
        
        $data = [
            'username' => $username,
            'fullname' => $fullname,
            'password' => $password,
            'email' => $email,
            'active' => 0,
            'created_at' => now()
        ];

        try {
            if ($this->user->exists([$username, $email])) {
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
        $result = $this->user->exists($credentials['user']);
        
        if ($result) {
            $user = $result[0];

            if ($this->hasher->check($credentials['password'], $user['password'])) {
                $this->session->put('user', $user['id']);
                $this->user->id = $this->session->get('user');
                return $this->user->getID();
            }
        }

        return false;
    }
    
    public function logout(): void
    {
        $this->session->forget('user');

        // $this->user = null;
    }
    
}