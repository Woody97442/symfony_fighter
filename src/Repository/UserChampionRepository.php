<?php

namespace App\Repository;

use App\Entity\UserChampion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserChampion>
 *
 * @method UserChampion|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserChampion|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserChampion[]    findAll()
 * @method UserChampion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserChampionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserChampion::class);
    }

//    /**
//     * @return UserChampion[] Returns an array of UserChampion objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?UserChampion
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
