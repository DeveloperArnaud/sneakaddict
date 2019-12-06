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


    public function findAllLimited()
    {

        return $this->createQueryBuilder('s')
            ->orderBy('s.added_at','DESC')
            ->setMaxResults(3)

            ->getQuery()
            ->getResult();

    }

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

    public function orderBy($query,$offset,$limit)
    {

        return $this->createQueryBuilder('s')
            ->orderBy('s.prix',$query)
            ->setMaxResults($limit)
            ->setFirstResult($offset)
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

    public function SearchByTaille($taille) {
        return $this->createQueryBuilder('s')
            ->join('s.taille', 't')
            ->where('t.taille = :taille')
            ->setParameter('taille',$taille)
            ->getQuery()
            ->getResult();


    }

    public function getSneakerByTailleIdandSneakerId($taille,$idSneaker) {
        return $this->createQueryBuilder('s')
            ->select('s,st')
            ->Join('s.taille', 'st')
            ->where('s.id = :idSneaker')
            ->AndWhere('st.id = :taille')
            ->setParameter('taille',$taille)
            ->setParameter('idSneaker',$idSneaker)
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
