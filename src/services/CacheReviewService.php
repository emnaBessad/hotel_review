<?php
namespace App\services;

use Symfony\Component\Cache\Adapter\FilesystemAdapter;

class CacheReviewService
{
    public $time=1;

    public function getCachedReview($id_hotel)
    {
        $cache = new FilesystemAdapter();
        $hotelCahe = $cache->getItem('todayReview'.$id_hotel);
        return $hotelCahe->get();
    }

    public function setCachedReview($id_hotel,$review)
    {
        $cache = new FilesystemAdapter();
        $hotelCahe = $cache->getItem('todayReview'.$id_hotel);
        $hotelCahe
            ->expiresAfter($this->time)
            ->set($review);
        $cache->save($hotelCahe);
        return true;
    }
}