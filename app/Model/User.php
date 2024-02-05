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

    /**
     * Update user data
     */
    public function updateUser($userId, $userData)
    {
        $data = [
            'fullname' => $userData['fname'],
            'website' => $userData['website'],
            'job' => $userData['job'],
            'bio' => $userData['bio'],
            'location' => $userData['location'],
            'twitter' => $userData['twitter'],
            'instagram' => $userData['instagram']
        ];
        return $this->update($userId, $data);
    }

    /**
     * Search users
     */
    public function searchUser(string $searchTerm)
    {
        $data = [
            '$or' => [
                ['fullname' => $searchTerm],
                ['username' => $searchTerm]
            ]
        ];
        return iterator_to_array($this->findAll($data));
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

    // Switch user theme
    public function setTheme(array $data)
    {
        $id = $this->createMongoId($data['id']);
        $theme = $data['theme'];

        try {
            $result = $this->collection->updateOne(
                ['_id' => $id],
                ['$set' => ['preferences' => [['theme' => $theme]]]]
            );

            if ($result->getModifiedCount() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
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
