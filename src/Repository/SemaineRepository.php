<?php

namespace App\Repository;

use App\Entity\Semaine;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Semaine>
 *
 * @method Semaine|null find($id, $lockMode = null, $lockVersion = null)
 * @method Semaine|null findOneBy(array $criteria, array $orderBy = null)
 * @method Semaine[]    findAll()
 * @method Semaine[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SemaineRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Semaine::class);
    }



    public function findById($id): array
    {
        return $this->createQueryBuilder('smn')
            ->andWhere('smn.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();
    }




    public function findByUser($userId): array
    {
        return $this->createQueryBuilder('smn')
            ->andWhere('smn.user = :userId')
            ->setParameter('userId', $userId)
            ->addOrderBy('smn.dateDebut', 'DESC')
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return Semaine[] Returns an array of Semaine objects
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

//    public function findOneBySomeField($value): ?Semaine
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
