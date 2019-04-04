<?php

namespace App\Repository;

use App\Entity\UsersMetas;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method UsersMetas|null find($id, $lockMode = null, $lockVersion = null)
 * @method UsersMetas|null findOneBy(array $criteria, array $orderBy = null)
 * @method UsersMetas[]    findAll()
 * @method UsersMetas[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UsersMetasRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, UsersMetas::class);
    }

    // /**
    //  * @return UsersMetas[] Returns an array of UsersMetas objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UsersMetas
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
