<?php

namespace App\Model;

use Exception;
use App\Core\Database;
use App\Core\Session;

class User
{
    public $id;
    private $conn;
    protected $length = 32;
    protected $table = 'users';
    public $profile_url = "https://api.dicebear.com/6.x/shapes/svg?seed=";
    
    public function __construct(
        private readonly Image $image,
        private readonly Database $db,
        private readonly Session $session,
    )
    {
        $this->db->setTable($this->table);

        if (!$this->conn) {
            $this->conn = $this->db->getDB();
        }
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
            $ud = $this->getUser();

            if (!empty($ud['avatar'])) {
                $this->image->delete($ud['avatar'], 'avatars');
            }

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
        $data = $this->db->select(
            conditions: [
                'OR' => [
                    'fullname' => $searchTerm,
                    'username' => $searchTerm
                ]
            ]
        );

        return iterator_to_array($data);
    }

    /**
     * Check by username or email
     */
    public function exists($user): array|bool
    {
        $result = $this->db->select(
            conditions: [
                'OR' => [
                    'username' => $user,
                    'email' => $user
                ]
            ]
        );

        if (count($result) > 1) {
            throw new Exception('Duplicate User Entry Found!');
        }

        return empty($result) ? false : $result[0];
    }

    /**
     * Create new user entry
     */
    public function create(array $data)
    {
        $username = $this->validateUsername($data['username']);
        $email = $this->validateEmail($data['email']);
        $password = password_hash($data['password'], PASSWORD_DEFAULT, ['cost' => 8]);
        
        $data = [
            'username' => $username,
            'fullname' => ucfirst($data['fullname']),
            'email'     => $email,
            'password' => $password,
            'active' => 0,
            'signup_time' => now(),
            'created_at' => now()
        ];

        return $this->db->insert($data);
    }

    public function update(int $userID, array $data)
    {
        $result = $this->db->update($data, ['id' => $userID]);
        return $result;
    }

    /**
     * Return current session user data
     */
    public function getUser()
    {
        return $this->db->getRowById($this->session->get('user'));
    }
    
    // Get user details
    public function getUserDetails(array $data)
    {
    }

    /**
     * Return user avatar URL
     */
    public function getUserAvatar(array|object $user)
    {
        if (empty($user['avatar'])) {
            return $this->profile_url . $user['id'];
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
        $ud = $this->getUser();
        $this->image->delete($ud['avatar'], 'avatars');
        $data = ['$unset' => ['avatar' => '']];
        return $this->update($id, $data);
    }

    /**
     * Validate user email
     */
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

    /**
     * Update user preferences
     */
    public function updatePreference(string $id, string $preference, $value)
    {
        try {
            $query = ['$set' => [
                "preferences.$preference" => $value
            ]];

            $result = $this->update($id, $query);

            if ($result > 0) {
                return true;
            } else {
                return false;
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
        
        return $result;
    }

}
