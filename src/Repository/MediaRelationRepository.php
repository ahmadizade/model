<?php

namespace App\Repository;

use App\Entity\MediaRelation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method MediaRelation|null find($id, $lockMode = null, $lockVersion = null)
 * @method MediaRelation|null findOneBy(array $criteria, array $orderBy = null)
 * @method MediaRelation[]    findAll()
 * @method MediaRelation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MediaRelationRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, MediaRelation::class);
    }

    // /**
    //  * @return MediaRelation[] Returns an array of MediaRelation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MediaRelation
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
