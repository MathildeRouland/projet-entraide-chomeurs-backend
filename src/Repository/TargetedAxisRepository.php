<?php

namespace App\Repository;

use App\Entity\TargetedAxis;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TargetedAxis>
 *
 * @method TargetedAxis|null find($id, $lockMode = null, $lockVersion = null)
 * @method TargetedAxis|null findOneBy(array $criteria, array $orderBy = null)
 * @method TargetedAxis[]    findAll()
 * @method TargetedAxis[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TargetedAxisRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TargetedAxis::class);
    }

    public function add(TargetedAxis $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(TargetedAxis $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return TargetedAxis[] Returns an array of TargetedAxis objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?TargetedAxis
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
