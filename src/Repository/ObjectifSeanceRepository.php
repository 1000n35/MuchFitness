<?php

namespace App\Repository;

use App\Entity\ObjectifSeance;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ObjectifSeance>
 *
 * @method ObjectifSeance|null find($id, $lockMode = null, $lockVersion = null)
 * @method ObjectifSeance|null findOneBy(array $criteria, array $orderBy = null)
 * @method ObjectifSeance[]    findAll()
 * @method ObjectifSeance[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ObjectifSeanceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ObjectifSeance::class);
    }

//    /**
//     * @return ObjectifSeance[] Returns an array of ObjectifSeance objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('o.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ObjectifSeance
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
