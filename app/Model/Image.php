<?php

namespace App\Model;

use Exception;

class Image
{
    private $image;
    private $imageType;
    private $supportedTypes = [
        'image/jpeg',
        'image/png'
    ];

    private array $image_errors = [
        UPLOAD_ERR_OK,
        UPLOAD_ERR_INI_SIZE,
        UPLOAD_ERR_FORM_SIZE,
        UPLOAD_ERR_PARTIAL,
        UPLOAD_ERR_NO_FILE,
        UPLOAD_ERR_NO_TMP_DIR,
        UPLOAD_ERR_CANT_WRITE,
        UPLOAD_ERR_EXTENSION
    ];


    public function addImage(object $image)
    {
        $this->image = $image;
        $this->imageType = $image->getClientMediaType();
    }

    public function exists(string $path)
    {
        return file_exists(STORAGE_PATH . DIRECTORY_SEPARATOR . $path);
    }

    public function save(object $image, string $pathCategory, $abs_path = false)
    {
        if (!isset($this->image, $this->imageType)) {
            $this->image = $image;
            $this->imageType = $image->getClientMediaType();
        }
        $tmpFilePath = $this->image->getFilePath();
        $fileExtension = image_type_to_extension(exif_imagetype($tmpFilePath));
        $newFileName = md5(mt_rand(1, 10000) . $fileExtension) . $fileExtension;
        $newFilePath = DIRECTORY_SEPARATOR . $pathCategory . DIRECTORY_SEPARATOR . $newFileName;
        $newFileLocation = STORAGE_PATH . $newFilePath;

        if (!$this->exists($pathCategory))
        {
            mkdir(STORAGE_PATH . DIRECTORY_SEPARATOR . $pathCategory);
        }

        if (!in_array($this->imageType, $this->supportedTypes)) {
            throw new Exception("Image type is not supported.");
        }

        if (move_uploaded_file($tmpFilePath, $newFileLocation)) {
            if ($abs_path) {
                return $newFileLocation;
            }
            return $newFileName;
        }

        throw new Exception("Image not uploaded!");
    }

    /**
     * Delete a image from given source
     */
    public function delete(string $imageName, string $pathCategory)
    {
        $image_path = STORAGE_PATH . DIRECTORY_SEPARATOR . $pathCategory . DIRECTORY_SEPARATOR . $imageName;
        if (file_exists($image_path)) {
            if (unlink($image_path)) {
                return true;
            } else {
                throw new Exception('Cannot remove image: ' . $image_path);
            }
        }
    }

    /**
     * Check file image upload error
     */
    public function checkError(object $image)
    {
        $errorCode = $image->getError();
        if (in_array($errorCode, $this->image_errors)) {
            if ($this->image_errors[$errorCode] == 0) {
                return true;
            }
            if ($this->image_errors[$errorCode] == 4) {
                return false;
            }
            throw new Exception("Image not uploaded. Error Code: " . $errorCode);
        }
    }

    /**
     * Crop from the image path
     * 
     * @param string $path absolute path of image
     * @return string basename($path)
     */
    public function cropAvatar(string $path)
    {
        $pathContents = file_get_contents($path);
        $img = imagecreatefromstring($pathContents);
        $ini_x_size = getimagesize($path)[0];
        $ini_y_size = getimagesize($path)[1];
        $crop_measure = min($ini_x_size, $ini_y_size);
        $crop_array = array('x' => 0, 'y' => 0, 'width' => $crop_measure, 'height' => $crop_measure);
        $cropped_img = imagecrop($img, $crop_array);

        // Writing the cropped image where the image is stored 
        imagejpeg($cropped_img, $path, 50);

        // Destroy to free up the memory
        if (imagedestroy($img)) {
            return basename($path);
        }

        throw new Exception("Image cannot be cropped.");
        
    }

    public function setCanvas()
    {
    }
}