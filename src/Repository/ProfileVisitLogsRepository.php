<?php

namespace App\Repository;

use App\Entity\ProfileVisitLogs;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ProfileVisitLogs|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProfileVisitLogs|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProfileVisitLogs[]    findAll()
 * @method ProfileVisitLogs[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProfileVisitLogsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ProfileVisitLogs::class);
    }

    // /**
    //  * @return ProfileVisitLogs[] Returns an array of ProfileVisitLogs objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ProfileVisitLogs
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
