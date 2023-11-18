<?php

namespace App\Repository;

use App\Entity\SuiviSeance;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SuiviSeance>
 *
 * @method SuiviSeance|null find($id, $lockMode = null, $lockVersion = null)
 * @method SuiviSeance|null findOneBy(array $criteria, array $orderBy = null)
 * @method SuiviSeance[]    findAll()
 * @method SuiviSeance[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SuiviSeanceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SuiviSeance::class);
    }

//    /**
//     * @return SuiviSeance[] Returns an array of SuiviSeance objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?SuiviSeance
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
