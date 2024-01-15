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
                // TODO: password hash verify
                $this->session->put('user', (string) $result['_id']);
                // $this->user->id = $this->session->get('user');
                // return $this->user->getID();
                return true;
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