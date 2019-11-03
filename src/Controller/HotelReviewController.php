<?php

namespace App\Controller;

use App\Repository\HotelRepository;
use App\Repository\ReviewRepository;
use App\services\CacheReviewService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Contracts\Cache\ItemInterface;

class HotelReviewController extends AbstractController
{

    private $repository;

    public function __construct(HotelRepository $repository)
    {
        $this->repository = $repository;
    }


    /**
     * @Route("/{hotelId}/today/review", name="hotel_review")
     */
    public function todayReview($hotelId,ReviewRepository $reviewRepository,CacheReviewService $cacheReviewService)
    {
        if($this->repository->findOneById($hotelId)==null)
            // throw exception when the id not found in Db
            throw $this->createNotFoundException('The Hotel is not found');

            // Cache the random review server-side for 1 minute
        if($cacheReviewService->getCachedReview($hotelId)==null){
            $review=$reviewRepository->getTodayRandomReview($hotelId);
            $cacheReviewService->setCachedReview($hotelId,$review);
        }
        else
            $review=$cacheReviewService->getCachedReview($hotelId);

        return $this->render('hotel_review/today_review.html.twig', [
            'review' => $review,
        ]);
    }
}
