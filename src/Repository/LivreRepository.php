<?php

namespace App\Repository;

use App\Entity\Livre;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Livre|null find($id, $lockMode = null, $lockVersion = null)
 * @method Livre|null findOneBy(array $criteria, array $orderBy = null)
 * @method Livre[]    findAll()
 * @method Livre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LivreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Livre::class);
    }

    // public function selectDateInterval($from, $to, $genre = null)
    public function selectDateInterval($from, $to)
    {
        $query = $this->getEntityManager()->createQuery("
            SELECT l FROM App\Entity\Livre l WHERE l.parution > :from AND l.parution < :to
        ")
            ->setParameter(':from', $from)
            ->setParameter(':to', $to)
        ;

        return $query->getResult();

        // $query = $this->createQueryBuilder('l')
        //     ->where('l.parution > :from')
        //     ->andWhere('l.parution < :to')
        //     ->setParameter(':from', $from)
        //     ->setParameter(':to', $to);
        //     if($genre != null) {
        //         $query->leftJoin('l.genre', 'g')
        //             ->andWhere('g.id = :genre')
        //             ->setParameter(':genre', $genre);
        //     }

        // return $query->getQuery()->getResult();
    }

    public function getPaginationLivre($page, $limit, $filtres = null)
    {
        $query = $this->createQueryBuilder('l')
            // ->where('where', 'l.isDisponible = 1')
            ;

            if ($filtres != null) {
                $query->andWhere('l.genre IN(:genre)')
                ->setParameter(':genre', array_values($filtres));
            }

            $query->orderBy('l.id')
                ->setFirstResult(($page * $limit) - $limit)
                ->setMaxResults($limit)
        ;

        return $query->getQuery()->getResult();
    }

    public function getTotalLivres($filtres = null)
    {
        $query = $this->createQueryBuilder('l')
            ->select('COUNT(l)');

        if ($filtres != null) {
        $query->andWhere('l.genre IN(:genre)')
            ->setParameter(':genre', array_values($filtres));
    }


            return $query->getQuery()->getSingleScalarResult();
    }

    // /**
    //  * @return Livre[] Returns an array of Livre objects
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
    public function findOneBySomeField($value): ?Livre
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
