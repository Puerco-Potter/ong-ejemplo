<?php

namespace App\Repository;

use App\Entity\Colaborator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Colaborator|null find($id, $lockMode = null, $lockVersion = null)
 * @method Colaborator|null findOneBy(array $criteria, array $orderBy = null)
 * @method Colaborator[]    findAll()
 * @method Colaborator[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ColaboratorRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Colaborator::class);
    }

//    /**
//     * @return Colaborator[] Returns an array of Colaborator objects
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
    public function findOneBySomeField($value): ?Colaborator
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
