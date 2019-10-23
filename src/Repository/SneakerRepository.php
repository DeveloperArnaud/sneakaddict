<?php

namespace App\Repository;

use App\Entity\Sneaker;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Sneaker|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sneaker|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sneaker[]    findAll()
 * @method Sneaker[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SneakerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sneaker::class);
    }

    // /**
    //  * @return Sneaker[] Returns an array of Sneaker objects
    //  */

    public function findByCouleur($couleur)
    {

           return $this->createQueryBuilder('s')
                ->where('s.couleur LIKE :couleur')
                ->setParameter('couleur', "$couleur%")
                ->getQuery()
                ->getResult();

    }

    public function findByTitre($query)
    {

        return $this->createQueryBuilder('s')
            ->where('s.titre LIKE :titre')
            ->setParameter('titre', "%$query%")
            ->getQuery()
            ->getResult();

    }

    public function GroupByColor()
    {

        return $this->createQueryBuilder('s')
            ->select('s.couleur')
            ->groupBy('s.couleur')
            ->getQuery()
            ->getResult();

    }


    /*
    public function findOneBySomeField($value): ?Sneaker
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
