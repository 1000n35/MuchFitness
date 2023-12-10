<?php

namespace App\Repository;

use App\Entity\SeanceType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SeanceType>
 *
 * @method SeanceType|null find($id, $lockMode = null, $lockVersion = null)
 * @method SeanceType|null findOneBy(array $criteria, array $orderBy = null)
 * @method SeanceType[]    findAll()
 * @method SeanceType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SeanceTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SeanceType::class);
    }

//    /**
//     * @return SeanceType[] Returns an array of SeanceType objects
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

//    public function findOneBySomeField($value): ?SeanceType
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

        public function findById($value): array
        {
            return $this->createQueryBuilder('p')
                ->andWhere('p.id = :val')
                ->setParameter('val', $value)
                ->getQuery()
                ->getResult()
            ;
}

}
