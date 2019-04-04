<?php

namespace App\Repository;

use App\Entity\ProfileVisits;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ProfileVisits|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProfileVisits|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProfileVisits[]    findAll()
 * @method ProfileVisits[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProfileVisitsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ProfileVisits::class);
    }

    // /**
    //  * @return ProfileVisits[] Returns an array of ProfileVisits objects
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
    public function findOneBySomeField($value): ?ProfileVisits
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
