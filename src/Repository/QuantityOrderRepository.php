<?php

namespace App\Repository;

use App\Entity\QuantityOrder;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method QuantityOrder|null find($id, $lockMode = null, $lockVersion = null)
 * @method QuantityOrder|null findOneBy(array $criteria, array $orderBy = null)
 * @method QuantityOrder[]    findAll()
 * @method QuantityOrder[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuantityOrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, QuantityOrder::class);
    }

    // /**
    //  * @return QuantityOrder[] Returns an array of QuantityOrder objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('q.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?QuantityOrder
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
