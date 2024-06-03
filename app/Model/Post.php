<?php

namespace App\Model;

use DateTime;
use Exception;
use ZipArchive;
use Carbon\Carbon;
use App\Model\Image;
use App\Core\Database;


class Post
{
    protected $table1 = 'posts';
    protected $table2 = 'post_images';
    protected $storage_path = STORAGE_PATH . '/posts/';

    public function __construct(
        private readonly Image $image,
        private readonly Database $db,
        private readonly ZipArchive $zip
    )
    {
        if (!file_exists($this->storage_path)) {
            mkdir($this->storage_path);
        }

        // Default table 1
        $this->db->setTable($this->table1);
    }

    public function getAllPosts($limit = 10)
    {
        // Select post data from the posts table
        $postData = $this->db->select(limit: $limit);

        // Switch to the post_images table
        $this->db->setTable($this->table2);

        // Select image data from the post_images table
        $imageData = $this->db->select();

        // Create an associative array to map post IDs to their images
        $imagesByPostId = [];
        foreach ($imageData as $img) {
            $postId = $img['post_id'];
            if (!isset($imagesByPostId[$postId])) {
                $imagesByPostId[$postId] = [];
            }
            $imagesByPostId[$postId][] = $img['image_uri'];
        }

        // Combine post data with their corresponding image data
        $newData = [];
        foreach ($postData as $post) {
            $postId = $post['id'];
            $images = isset($imagesByPostId[$postId]) ? $imagesByPostId[$postId] : [];
            $post['images'] = $images;
            $newData[] = $post;
        }

        return $newData;
    }

    /**
     * Get user posts by user ID
     */
    public function getUserPosts(string $user_id)
    {
        $postData = $this->db->getRowById($user_id, 'uid');
        
        $this->db->setTable($this->table2);

        $postData['uploaded_time'] = $this->getHumanTime($postData['uploaded_time']);
        $postData['images'] = $this->db->select(['image_uri'], ['post_id' => $postData['id']]);

        $this->db->setTable($this->table1);

        return $postData;
    }

    public function getLatestPosts(int $limit = 10)
    {
        return $this->db->select(orderBy: 'uploaded_time DESC', limit: $limit);
    }

    /**
     * Fetch posts using offset
     */
    // public function fetchPosts($limit = 10, $offset)
    // {
    //     return $this->db->select(
    //         limit: $limit,
    //         offset: $offset,
    //         orderBy: 'uploaded_time DESC'
    //     );
    // }

    /**
     * Get Human readable time format
     */
    public function getHumanTime(string $timestamp)
    {
        $time = Carbon::parse($timestamp);
        return $time->diffForHumans();
    }

    /**
     * Get single post by its ID
     */
    public function getPostById(string $pid)
    {
        $postData = $this->db->getRowById($pid, 'id');

        $this->db->setTable($this->table2);

        $postData['images'] = $this->db->select(['image_uri'], ['post_id' => $postData['id']]);

        return $postData;
    }

    /**
     * Return list of users data
     */
    public function getUsersByIds(array $userIds)
    {
    }

    /**
     * Create post
     */
    public function createPost(array $data)
    {
        $multiple = (count($data['images']) > 1) ? 1 : 0;
        
        $postData = [
            'uid' => $data['user_id'],
            'caption' => $data['text'],
            'multiple' => $multiple,
            'uploaded_time' => now()
        ];
        $this->db->setTable($this->table1);
        $post_id = $this->db->insert($postData);

        $this->db->setTable($this->table2);

        foreach ($data['images'] as $image_path) {
            $imageName = $this->storeImage($image_path);
            $imagesData = [
                'post_id' => $post_id,
                'image_uri' => $imageName
            ];
            $this->db->insert($imagesData);
        }     

        $this->db->setTable($this->table1);
        
        return true;
    }

    public function getPostZip(string $postId)
    {
        $images = $this->getPostImages($postId);

        if (!$images) {
            return false;
        }

        $name = $this->getZipFileName();
        $tempPath = STORAGE_PATH . DIRECTORY_SEPARATOR . 'temp';
        $zipPath = $tempPath . DIRECTORY_SEPARATOR . $name;
        if (!file_exists($tempPath)) {
            mkdir($tempPath);
        }
        if ($this->zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            return false;   // Unable to open the zip file
        }

        foreach ($images as $image) {
            $path = $this->storage_path . $image;
            $this->zip->addFile($path, $image);
        }

        $this->zip->close();
        return $zipPath;
    }

    private function getZipFileName()
    {
        $formattedDateTime = (new DateTime())->format('His');
        $randomString = bin2hex(random_bytes(4));
        $name = "Photogram_Image_{$formattedDateTime}{$randomString}.zip";
        return $name;
    }

    /**
     * Delete post with images
     */
    public function deletePost(string $id)
    {
    }

    public function updatePostText(string $id, string $text)
    {
        if (!empty($text) && strlen($text) >= 240) {
            return false;
        }
        
        $data = ['$set' => ['caption' => $text]];

        // $this->update($id, $data);

        return true;
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

    /**
     * Return the list of image names related to post
     */
    protected function getPostImages(string $pid)
    {
        // $post = $this->findById($pid);
        // if ($post !== null) {
        //     return iterator_to_array($post['images']);
        // } else {
        //     return false;
        // }
    }

    /**
     * Delete Images from Storage
     */
    public function deleteImage(array $data)
    {
        try {
            foreach ($data['images'] as $image) {
                $image_path = $this->storage_path . $image;
                if (file_exists($image_path)) {
                    if (unlink($image_path)) {
                        continue;
                    } else {
                        throw new Exception('Cannot remove image: ' . $image_path);
                    }
                }
            }
            return true;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Toggle post likes
     */
    public function toggleLikes(string $pid, string $uid)
    {        
    }

    /**
     * Get liked users data
     */
    public function getLikedUsers(string $pid)
    {
    }

    /**
     * Get total likes of a user
     */
    public function getUserLikesCount(string $user_id)
    {
    }

    /**
     * Add comments to post
     */
    public function addComment(string $pid, string $uid, string $text)
    {
    }

    /**
     * Fetch comments for given post ID
     */
    public function fetchComments(string $pid)
    {
    }

    /**
     * Delete a comment
     */
    public function deleteComment(string $pid, string $cid)
    {        
    }
}
