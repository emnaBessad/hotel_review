<?php

namespace App\Repository;

use App\Entity\Hotel;
use App\Entity\Review;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Review|null find($id, $lockMode = null, $lockVersion = null)
 * @method Review|null findOneBy(array $criteria, array $orderBy = null)
 * @method Review[]    findAll()
 * @method Review[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReviewRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Review::class);
    }

    /**
     * @return Review Returns a today random review
     */

    public function getTodayRandomReview($id_hotel)
    {
        $hotelRepository = $this
            ->getEntityManager()
            ->getRepository(Hotel::class);
        $offset = intval(rand(0, $hotelRepository->getNbReviews($id_hotel) - 1));
        return $this->createQueryBuilder('r')
            ->join('r.hotel' , 'h')
            ->where('h.id = :id')
            ->setParameter('id', $id_hotel)
            ->andWhere('r.creation_date >= :today')
            ->setParameter('today', (new \DateTime())->format('Y-m-d'))
            ->andWhere('r.creation_date < :tomorrow')
            ->setParameter('tomorrow', (new \DateTime())->modify('+1 day')->format('Y-m-d'))
            ->setFirstResult($offset)
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleResult();
    }

    // /**
    //  * @return Review[] Returns an array of Review objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Review
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
