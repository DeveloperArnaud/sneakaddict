<?php

namespace App\Repository;

use App\Entity\SneakerColor;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method SneakerColor|null find($id, $lockMode = null, $lockVersion = null)
 * @method SneakerColor|null findOneBy(array $criteria, array $orderBy = null)
 * @method SneakerColor[]    findAll()
 * @method SneakerColor[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SneakerColorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SneakerColor::class);
    }

    // /**
    //  * @return SneakerColor[] Returns an array of SneakerColor objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SneakerColor
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
