<?php

namespace App\Model;

use Exception;
use App\Core\Model;
use Carbon\Carbon;

class Post extends Model
{
    protected $collectionName = 'posts';
    protected $storage_path = STORAGE_PATH . '/posts/';

    public function __construct($mongoDB)
    {
        parent::__construct($mongoDB, $this->collectionName);
        if (!file_exists($this->storage_path)) {
            mkdir($this->storage_path);
        }
    }

    /**
     * Get user posts by user ID
     */
    public function getPostsById(string|object $user_id)
    {
        $cursor = $this->findById($user_id, 'user_id', false, true);

        $posts = iterator_to_array($cursor);

        usort($posts, function ($a, $b) {
            return strtotime($b->created_at) - strtotime($a->created_at);
        });

        $userData = $this->getUsersByIds([$user_id]);

        $formattedPosts = [];

        foreach ($posts as $post) {
            $time = Carbon::parse($post->created_at);
            $formattedPost = (array)$post;
            $formattedPost['created_at'] = $time->diffForHumans();
            $formattedPost['likes'] = count($post->likes);
            $formattedPost['userData'] = $userData[$post->user_id] ?? null;
            $formattedPosts[] = $formattedPost;
        }

        return $formattedPosts;
    }

    public function getAllPosts()
    {
        $cursor = $this->findAll();

        $posts = iterator_to_array($cursor);
        usort($posts, function ($a, $b) {
            return strtotime($b->created_at) - strtotime($a->created_at);
        });

        $userIds = array_column($posts, 'user_id');
        $userData = $this->getUsersByIds($userIds);

        $formattedPosts = [];

        foreach ($posts as $post) {
            $time = Carbon::parse($post->created_at);
            $formattedPost = (array)$post;
            $formattedPost['created_at'] = $time->diffForHumans();
            $formattedPost['likes'] = count($post->likes);
            $formattedPost['userData'] = $userData[$post->user_id] ?? null;
            $formattedPosts[] = $formattedPost;
        }

        return $formattedPosts;
    }

    /**
     * Return list of users data
     */
    public function getUsersByIds(array $userIds)
    {
        $objectIds = array_map(function ($userId) {
            return $this->createMongoId($userId);
        }, $userIds);

        $c = $this->db->selectCollection('users');
        $users = $c->find(['_id' => ['$in' => $objectIds]]);
        $userData = [];

        foreach ($users as $user) {
            $userData[(string)$user->_id] = $user;
        }

        return $userData;
    }

    public function handleImages(array $data)
    {
        $output = array();

        foreach ($data as $key => $values) {
            for ($i = 0; $i < count($values); $i++) {
                $output[$i][$key] = $values[$i];
            }
        }

        return $output;
    }

    public function createPost(array $data)
    {
        $images = $this->handleImages($data['images']);
        $url = [];

        foreach ($images as $image) {
            $path = $this->storeImage($image['tmp_name']);
            $url[] = $path;
        }

        $schema = [
            'user_id' => $data['user_id'],
            'images' => $url,
            'caption' => $data['text'],
            'likes' => [],
            'comments' => [],
            'created_at' => now(),
            'updated_at' => now(),
        ];
        return $this->create($schema);
    }

    public function storeImage(string $image_tmp)
    {
        if (is_file($image_tmp) && exif_imagetype($image_tmp) !== false) {
            $name = md5(time() . mt_rand(0, 99999));
            $ext = image_type_to_extension(exif_imagetype($image_tmp));
            $image = $name . $ext;
            $image_path = $this->storage_path . $image;

            if (move_uploaded_file($image_tmp, $image_path)) {
                return $image;
            }

            throw new Exception("Can't move the uploaded file");
        } else {
            throw new Exception("Not a valid image path!");
        }
    }

    public function getImage(string $image)
    {
        $filePath = $this->storage_path . $image;
        if (file_exists($filePath) && is_file($filePath)) {
            return file_get_contents($filePath);
        }

        return false;
    }
}
