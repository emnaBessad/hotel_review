<?php

namespace App\Repository;

use App\Entity\Hotel;
use App\Entity\Review;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Hotel|null find($id, $lockMode = null, $lockVersion = null)
 * @method Hotel|null findOneBy(array $criteria, array $orderBy = null)
 * @method Hotel[]    findAll()
 * @method Hotel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HotelRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Hotel::class);
    }

     /**
      * @return integer Returns a total number of reviews
      */

    public function getNbReviews($id_hotel)
    {
        return $this->createQueryBuilder('h')
            ->innerJoin('h.reviews','r')
            ->where('h.id = :id')
            ->setParameter('id', $id_hotel)
            ->select('count(r.id)')
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    /**
     * @return Review Returns a today random review
     */

    public function getTodayRandomReview($id_hotel)
    {
        $offset = intval(rand(0, $this->getNbReviews($id_hotel) - 1));
        return $this->createQueryBuilder('h')
            ->innerJoin(Review::class,'r','r.hotel_id	= h.id')
            ->where('h.id = :id')
            ->setParameter('id', $id_hotel)
            ->andWhere('r.creation_date = :today')
            ->setParameter('today', (new \DateTime())->format('Y-m-d'))
            ->setFirstResult($offset)
            ->getQuery()
            ->getResult();
    }

    /*
    public function findOneBySomeField($value): ?Hotel
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
