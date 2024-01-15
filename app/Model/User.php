<?php

namespace App\Model;

use App\Core\Model;

class User extends Model
{
    protected $collectionName = 'users';
    
    public function __construct($mongoDB)
    {
        parent::__construct($mongoDB, $this->collectionName);
    }

    // Update user
    // TODO: Test this code
    public function updateUser($userId, $userData)
    {
        $this->update($userId, $userData);
    }

    /**
     * Check by username or email
     */
    public function exists(array $data)
    {
        $email = ['email' => $data['email']];
        $username = ['username' => $data['username']];
        $data = [];

        if ($this->findOne($username) !== null) {
            $data['user'] = $this->findOne($username);
        }

        if ($this->findOne($email) !== null) {
            $data['user'] = $this->findOne($email);
        }

        return $data['user'] ?? false;
    }

    public function getUser()
    {
        return $this->findById($_SESSION['user']);
    }
    
    // Get user details
    public function getUserDetails(array $data)
    {
        return $this->findOne($data);
    }

    public function validateEmail(string $email)
    {
        return filterEmail($email);
    }

    /**
     * Sanitize the username according to the app needs
     */
    public function validateUsername(string $username)
    {
        // Replace whitespace with underscore
        $username = str_replace(' ', '_', trim($username));

        return strtolower($username);
    }

    // Other model-specific methods
}
