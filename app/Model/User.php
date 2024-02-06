<?php

namespace App\Model;

use App\Core\Model;
use App\Core\MongoDB;
use Exception;

class User extends Model
{
    protected $collectionName = 'users';
    public $profile_url = "https://api.dicebear.com/6.x/shapes/svg?seed=";
    
    public function __construct(
        private readonly Image $image, 
        private MongoDB $mongoDB
    )
    {
        parent::__construct($mongoDB, $this->collectionName);
    }

    /**
     * Update user data
     */
    public function updateUser($userId, $userData, object $avatar)
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
                
        if ($this->image->checkError($avatar)) {
            $category = 'avatars';
            $this->image->addImage($avatar);
            $path = $this->image->save($avatar, $category, true);
            $imgName = $this->image->cropAvatar($path);
            $data['avatar'] = $imgName;
        }

        $data = ['$set' => $data];
        
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

    /**
     * Return current session user data
     */
    public function getUser()
    {
        return $this->findById($_SESSION['user']);
    }
    
    // Get user details
    public function getUserDetails(array $data)
    {
        return $this->findOne($data);
    }

    /**
     * Return user avatar URL
     */
    public function getUserAvatar(object $user)
    {
        if (empty($user['avatar'])) {
            return $this->profile_url . (string)$user['_id'];
        }
        return '/files/avatars/' . $user['avatar'];
    }

    /**
     * Dumps avatar image contents
     */
    public function getAvatar(string $image)
    {
        $filePath = STORAGE_PATH . DIRECTORY_SEPARATOR . $image;
        if (file_exists($filePath) && is_file($filePath)) {
            return file_get_contents($filePath);
        }

        return false;
    }

    /**
     * Delete avatar image
     */
    public function deleteAvatar(string $id)
    {
        $ud = $this->findOne([
            '_id' => $this->createMongoId($id)
        ]);
        $this->image->delete($ud->avatar, 'avatars');
        $data = ['$unset' => ['avatar' => '']];
        return $this->update($id, $data);
    }

    /**
     * Switch user theme
     */
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
