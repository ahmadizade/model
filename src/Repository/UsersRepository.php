<?php

namespace App\Repository;

use App\Entity\Users;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Users|null find($id, $lockMode = null, $lockVersion = null)
 * @method Users|null findOneBy(array $criteria, array $orderBy = null)
 * @method Users[]    findAll()
 * @method Users[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UsersRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Users::class);
    }

//    /**
//     * @return Users[] Returns an array of Users objects
//     */
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
    public function findOneBySomeField($value): ?Users
    {
        return $this->createQueryBuilder('u')
            ->andGWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function isDuplicate($phone): ?Users
    {
        return $this->createQueryBuilder('u')
            ->orWhere('u.phone = :phone')
            ->setParameter('phone', $phone)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    public function authenticatePhoneOrEmail($username, $password): ?Users
    {
        try{
            return $this->createQueryBuilder('u')
                ->where('(u.phone = :phone OR u.email = :email) AND (u.password = :password)')
                ->setParameter('phone', $username)
                ->setParameter('email', $username)
                ->setParameter('password', $password)
                ->getQuery()
                ->getOneOrNullResult()
                ;
        }catch (\Exception $exception){
            return null;
        }
    }

    public function authenticatePhone($username, $password): ?Users
    {
        try{
            return $this->createQueryBuilder('u')
                ->where('(u.phone = :phone) AND (u.password = :password)')
                ->setParameter('phone', $username)
                ->setParameter('password', $password)
                ->getQuery()
                ->getOneOrNullResult()
                ;
        }catch (\Exception $exception){
            return null;
        }
    }

    public function authenticateEmail($username, $password): ?Users
    {
        try{
            return $this->createQueryBuilder('u')
                ->where('(u.email = :email) AND (u.password = :password)')
                ->setParameter('email', $username)
                ->setParameter('password', $password)
                ->getQuery()
                ->getOneOrNullResult()
                ;
        }catch (\Exception $exception){
            return null;
        }
    }
}
