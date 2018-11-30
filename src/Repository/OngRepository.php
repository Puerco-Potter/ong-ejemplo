<?php

namespace App\Repository;

use App\Entity\Ong;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Ong|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ong|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ong[]    findAll()
 * @method Ong[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OngRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Ong::class);
    }

//    /**
//     * @return Ong[] Returns an array of Ong objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Ong
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findOneOngByTematica($tematica_id): ?Ong
    {
        return $this->createQueryBuilder('o')
            ->join('o.tematicas', 't',  'WITH', 't.id = :tematica')       
            ->join('o.user', 'u')
            ->setParameter('tematica',$tematica_id)
            ->getQuery()
            ->getResult();
        
    }
}
