<?php

namespace App\Repository;

use App\Entity\Libraire;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Libraire|null find($id, $lockMode = null, $lockVersion = null)
 * @method Libraire|null findOneBy(array $criteria, array $orderBy = null)
 * @method Libraire[]    findAll()
 * @method Libraire[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LibraireRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Libraire::class);
    }

    // /**
    //  * @return Libraire[] Returns an array of Libraire objects
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
    public function findOneBySomeField($value): ?Libraire
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
