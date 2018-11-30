<?php

namespace App\Repository;

use App\Entity\ComentarioDebate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ComentarioDebate|null find($id, $lockMode = null, $lockVersion = null)
 * @method ComentarioDebate|null findOneBy(array $criteria, array $orderBy = null)
 * @method ComentarioDebate[]    findAll()
 * @method ComentarioDebate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ComentarioDebateRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ComentarioDebate::class);
    }

//    /**
//     * @return ComentarioDebate[] Returns an array of ComentarioDebate objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ComentarioDebate
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
