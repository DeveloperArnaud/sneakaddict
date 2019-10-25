<?php

namespace App\Repository;

use App\Entity\Favoris;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Favoris|null find($id, $lockMode = null, $lockVersion = null)
 * @method Favoris|null findOneBy(array $criteria, array $orderBy = null)
 * @method Favoris[]    findAll()
 * @method Favoris[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FavorisRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Favoris::class);
    }

    // /**
    //  * @return Favoris[] Returns an array of Favoris objects
    //  */

    public function findByUserId($id)
    {
        return $this->createQueryBuilder('f')
            ->innerJoin('f.user','u')
            ->where('u.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();
    }

    public function findByUserIdAndSneakerId($id,$idSneaker)
    {
        return $this->createQueryBuilder('f')
            ->innerJoin('f.user','u')
            ->innerJoin('f.sneaker','s')
            ->where('u.id = :id')
            ->andWhere('s.id = :idSneaker')
            ->setParameter('id', $id)
            ->setParameter('idSneaker', $idSneaker)
            ->getQuery()
            ->getResult();
    }

    public function findByIdandUserIdAndSneakerId($idFavoris,$idUser,$idSneaker)
    {
        return $this->createQueryBuilder('f')
            ->innerJoin('f.user','u')
            ->innerJoin('f.sneaker','s')
            ->where('f.id = :idFavoris')
            ->andWhere('u.id = :idUser')
            ->andWhere('s.id = :idSneaker')
            ->setParameter('idUser', $idUser)
            ->setParameter('idSneaker', $idSneaker)
            ->setParameter('idFavoris', $idFavoris)

            ->getQuery()
            ->getResult();
    }


    /*
    public function findOneBySomeField($value): ?Favoris
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
