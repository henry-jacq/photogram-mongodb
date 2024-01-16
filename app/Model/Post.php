<?php

namespace App\Model;

use Exception;
use App\Core\Model;


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
            $name = md5(time().mt_rand(0, 99999));
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
}