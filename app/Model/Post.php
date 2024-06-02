<?php

namespace App\Model;

use DateTime;
use Exception;
use ZipArchive;
use Carbon\Carbon;


class Post
{
    protected $collectionName = 'posts';
    protected $storage_path = STORAGE_PATH . '/posts/';

    public function __construct(
        private readonly ZipArchive $zip
    )
    {
        if (!file_exists($this->storage_path)) {
            mkdir($this->storage_path);
        }
    }

    /**
     * Get user posts by user ID
     */
    public function getUserPosts(string $user_id)
    {
    }

    public function getLatestPosts(int $limit = 10)
    {
    }

    public function fetchPosts($limit, $skip)
    {
    }

    /**
     * Get Human readable time format
     */
    public function getHumanTime(string $timestamp)
    {
        $time = Carbon::parse($timestamp);
        return $time->diffForHumans();
    }

    /**
     * Get post by its ID
     */
    public function getPostById(string $pid)
    {
        // $result = $this->findById($pid);
        // return $result;
    }

    /**
     * Return list of users data
     */
    public function getUsersByIds(array $userIds)
    {
    }

    public function createPost(array $data)
    {
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
