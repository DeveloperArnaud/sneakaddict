<?php

namespace App\Repository;

use App\Entity\Avis;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Avis|null find($id, $lockMode = null, $lockVersion = null)
 * @method Avis|null findOneBy(array $criteria, array $orderBy = null)
 * @method Avis[]    findAll()
 * @method Avis[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AvisRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Avis::class);
    }

    // /**
    //  * @return Avis[] Returns an array of Avis objects
    //  */

    public function findBySneakerId($idSneaker)
    {
        return $this->createQueryBuilder('a')
            ->join('a.sneaker','s')
            ->Where('s.id = :idSneaker')
            ->setParameter('idSneaker', $idSneaker)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByIdandUserIdAndSneakerId($idFavoris,$idUser,$idSneaker)
    {
        return $this->createQueryBuilder('a')
            ->innerJoin('a.user','u')
            ->innerJoin('a.sneaker','s')
            ->where('a.id = :idAvis')
            ->andWhere('u.id = :idUser')
            ->andWhere('s.id = :idSneaker')
            ->setParameter('idUser', $idUser)
            ->setParameter('idSneaker', $idSneaker)
            ->setParameter('idAvis', $idFavoris)

            ->getQuery()
            ->getResult();
    }


    public function findByUserId($id)
    {
        return $this->createQueryBuilder('a')
            ->innerJoin('a.user','u')
            ->where('u.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();
    }


    /*
    public function findOneBySomeField($value): ?Avis
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
