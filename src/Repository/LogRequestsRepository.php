<?php

namespace App\Repository;

use App\Entity\LogRequests;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method LogRequests|null find($id, $lockMode = null, $lockVersion = null)
 * @method LogRequests|null findOneBy(array $criteria, array $orderBy = null)
 * @method LogRequests[]    findAll()
 * @method LogRequests[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LogRequestsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, LogRequests::class);
    }

    // /**
    //  * @return LogRequests[] Returns an array of LogRequests objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?LogRequests
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
