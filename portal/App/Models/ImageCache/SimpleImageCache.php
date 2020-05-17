<?php

namespace App\Models\ImageCache;

class SimpleImageCache implements IImageCache
{
    private const CACHE_PATH = __DIR__."/../../Cache/";
    public function saveImages($images)
    {
        $ids = [];
        foreach($images["tmp_name"] as $imageName) {
            $imageId = uniqid().".jpg";
            if(@move_uploaded_file($imageName, self::CACHE_PATH."/$imageId")) {
                $ids[] = $imageId;
            } else {
                $this->deleteImages($ids);
                return null;
            }
        }
        
        return $ids;
    }

    public function getImage($imageId)
    {
        return fopen(self::CACHE_PATH."/$imageId", "r");
    }

    public function deleteImages(array $imageIds)
    {
        foreach($imageIds as $imageId) {
            unlink(self::CACHE_PATH."/$imageId");
        }
    }
}