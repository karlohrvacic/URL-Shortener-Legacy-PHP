<?php

namespace App\Repository;

use App\Entity\URL;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method URL|null find($id, $lockMode = null, $lockVersion = null)
 * @method URL|null findOneBy(array $criteria, array $orderBy = null)
 * @method URL[]    findAll()
 * @method URL[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class URLRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, URL::class);
    }

    // /**
    //  * @return URL[] Returns an array of URL objects
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
    public function findOneBySomeField($value): ?URL
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
