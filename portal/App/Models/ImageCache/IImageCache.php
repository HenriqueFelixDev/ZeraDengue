<?php

namespace App\Models\ImageCache;

interface IImageCache
{
    public function saveImages($image);
    public function getImage($imageId);
    public function deleteImages(array $imageIds);
}