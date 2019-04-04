<?php

namespace App\Repository;

use App\Entity\FrindshipRequests;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method FrindshipRequests|null find($id, $lockMode = null, $lockVersion = null)
 * @method FrindshipRequests|null findOneBy(array $criteria, array $orderBy = null)
 * @method FrindshipRequests[]    findAll()
 * @method FrindshipRequests[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FrindshipRequestsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, FrindshipRequests::class);
    }

    // /**
    //  * @return FrindshipRequests[] Returns an array of FrindshipRequests objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?FrindshipRequests
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
