<?php

namespace App\Repository;

use App\Data\SearchData;
use App\Entity\Sneaker;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @method Sneaker|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sneaker|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sneaker[]    findAll()
 * @method Sneaker[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SneakerRepository extends ServiceEntityRepository
{
    /**
     * @var PaginatorInterface
     */
    private $paginator;

    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Sneaker::class);
        $this->paginator = $paginator;
    }

    // /**
    //  * @return Sneaker[] Returns an array of Sneaker objects
    //  */



    public function findSearch(SearchData $search) : PaginationInterface {

        $query = $this->getSearchQuery($search)->getQuery();
        return $this->paginator->paginate($query,$search->page,9);
    }


    public function findMinMax(SearchData $searchData) : array {

        $results = $this->getSearchQuery($searchData, true)
            ->select('MIN(s.prix) as min , MAX(s.prix) as max')
            ->getQuery()
            ->getScalarResult();
        return [(int)$results[0]['min'],(int)$results[0]['max']];
    }

    public function getSearchQuery(SearchData $search, $ignorePrix = false) {
        $query = $this->createQueryBuilder('s')
            ->select('s' , 'c','t')
            ->join('s.colors','c')
            ->join('s.taille','t');


        if(!empty($search->q)) {
            $query = $query->andWhere('s.titre LIKE :q')
                ->setParameter('q',"%{$search->q}%");
        }

        if(!empty($search->min) && $ignorePrix === false ) {
            $query  = $query->andWhere('s.prix >= :min')
                ->setParameter('min',$search->min);
        }

        if(!empty($search->max)&& $ignorePrix === false) {
            $query  = $query->andWhere('s.prix <= :max')
                ->setParameter('max',$search->max);
        }

        if(!empty($search->couleurs)) {
            $query  = $query->andWhere('c.id IN (:colors)')
                ->setParameter('colors',$search->couleurs);
        }

        if(!empty($search->tailles)) {
            $query  = $query->andWhere('t.id IN (:tailles)')
                ->setParameter('tailles',$search->tailles);
        }

        return $query;
    }




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
