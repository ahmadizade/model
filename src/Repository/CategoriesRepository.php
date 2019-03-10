<?php

namespace App\Repository;

use App\Entity\Categories;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Categories|null find($id, $lockMode = null, $lockVersion = null)
 * @method Categories|null findOneBy(array $criteria, array $orderBy = null)
 * @method Categories[]    findAll()
 * @method Categories[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoriesRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Categories::class);
    }

    // /**
    //  * @return Categories[] Returns an array of Categories objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Categories
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findOneBySomeField()
    {


          return  $this->createQueryBuilder('c')
              ->addSelect('e')
              ->addSelect('d')
              ->innerJoin('c.toursToCategories','e')
              ->innerJoin('e.tour_ref_id','d')
              ->getQuery()->getSQL(/*\Doctrine\ORM\Query::HYDRATE_ARRAY*/)
            ;




    }


    public function findTours(){
        $p =  $this->getEntityManager()
            ->createQuery(
                'SELECT p.category_id,tour.tour_id
                    FROM App\Entity\Categories p 
                    INNER join App\Entity\ToursToCategories c with c.category_ref_id = p.category_id
                    INNER join App\Entity\Tours tour with tour.tour_id = c.tour_ref_id'
            )
            ->getResult();

        dump($p);exit;
    }
}
