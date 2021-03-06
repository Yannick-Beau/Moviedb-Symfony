<?php

namespace App\Repository;

use App\Entity\Movie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Movie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Movie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Movie[]    findAll()
 * @method Movie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MovieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Movie::class);
    }
    // Version Query Builder
    public function findByOrderedByTitleAsc()
    {
        return $this->createQueryBuilder('m')
            ->orderBy('m.title', 'ASC')
            ->getQuery()
            ->getResult();
    }

    // Version DQL
    // public function findByOrderedByTitleAsc()
    // {
    //     $entityManager = $this->getEntityManager();
    //     return $entityManager->createQuery(
    //         'SELECT m
    //            from App\Entity\movie m 
    //            ORDER BY m.title ASC')
    //         ->getResult();
    // }


    
    /*
    public function findOneBySomeField($value): ?Movie
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
