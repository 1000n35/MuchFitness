<?php

namespace App\Repository;

use App\Entity\Programme;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Programme>
 *
 * @method Programme|null find($id, $lockMode = null, $lockVersion = null)
 * @method Programme|null findOneBy(array $criteria, array $orderBy = null)
 * @method Programme[]    findAll()
 * @method Programme[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProgrammeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Programme::class);
    }



    public function findFavoritesByUser($userId): array
    {
        return $this->createQueryBuilder('p')
            ->leftJoin('p.estFavori', 'u')
            ->andWhere(':userId MEMBER OF p.estFavori')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getResult();
    }



    public function findByFilters($type, $nbJour, $dureeMax, $favoris, $mesprogs, $userId): array
    {
        $queryBuilder =$this->createQueryBuilder('p')
            ->leftJoin('p.seanceTypes', 's')            // requis pour nbjour, dureemax
            ->groupBy('p.id')                           // requis pour nbjour, dureemax
            ->leftJoin('p.estFavori', 'u');             // requis pour favoris

        if ($type) {
            // Filtre sur le type de programme
            $queryBuilder->andWhere('p.type = :type')
                ->setParameter('type', $type);
        }

        if ($nbJour) {
            // Filtre sur le nombre de jours (nombre de séances)
            $queryBuilder->andHaving('COUNT(s) = :nbJour')
                ->setParameter('nbJour', $nbJour);
        }

        if ($dureeMax) {
            // Filtre la duree max des séances d'un programme
            $queryBuilder->andHaving('MAX(s.duree) <= :dureeMax')
                ->setParameter('dureeMax', $dureeMax);
        }

        if ($favoris) {
            // Filtre favoris
            $queryBuilder->andWhere(':userId MEMBER OF p.estFavori')
                ->setParameter('userId', $userId);
        }

        if ($mesprogs) {
            // Filtre mes programmes
            $queryBuilder->andWhere('p.createur = :userId')
                ->setParameter('userId', $userId);
        }
    
        return $queryBuilder->getQuery()->getResult();

    }

    

//    /**
//     * @return Programme[] Returns an array of Programme objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Programme
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
