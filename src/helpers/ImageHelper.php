<?php

namespace Helpers;

class ImageHelper
{
    public static function validateImage($file)
    {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
        $allowedExtensions = ['jpeg', 'jpg', 'png'];
        $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $imageSize = $file['size'];
        $imageType = $file['type'];

        if (!in_array($imageType, $allowedTypes) || !in_array($fileExtension, $allowedExtensions)) {
            return ["success" => false, "message" => "Invalid image type. Only JPEG, JPG, and PNG files are allowed."];
        }

        if ($imageSize > 2 * 1024 * 1024) {
            return ["success" => false, "message" => "Image file size exceeds the 2MB limit."];
        }

        return ["success" => true];
    }

    public static function uploadImage($file, $uploadDir)
    {
        $imageName = basename($file['name']);
        $imageTmpPath = $file['tmp_name'];

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $imagePath = $uploadDir . $imageName;

        if (move_uploaded_file($imageTmpPath, $imagePath)) {
            return ["success" => true, "image" => $imageName];
        }

        return ["success" => false, "message" => "Failed to upload image."];
    }
}
