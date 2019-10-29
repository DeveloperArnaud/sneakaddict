<?php

namespace App\Repository;

use App\Entity\Quantity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Quantity|null find($id, $lockMode = null, $lockVersion = null)
 * @method Quantity|null findOneBy(array $criteria, array $orderBy = null)
 * @method Quantity[]    findAll()
 * @method Quantity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuantityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Quantity::class);
    }

    // /**
    //  * @return Quantity[] Returns an array of Quantity objects
    //  */

    public function findBySneakerIdAndTailleId($idSneaker,$idTaille)
    {
        return $this->createQueryBuilder('q')
            ->innerJoin('q.chaussure','s')
            ->innerJoin('q.tailles  ','t')
            ->where('s.id = :idSneaker')
            ->andWhere('t.id = :idTaille')
            ->setParameter('idSneaker', $idSneaker)
            ->setParameter('idTaille',$idTaille)
            ->getQuery()
            ->getResult()
        ;
    }

    /*
    public function findOneBySomeField($value): ?Quantity
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
