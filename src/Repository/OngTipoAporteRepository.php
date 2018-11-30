<?php

namespace App\Repository;

use App\Entity\OngTipoAporte;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method OngTipoAporte|null find($id, $lockMode = null, $lockVersion = null)
 * @method OngTipoAporte|null findOneBy(array $criteria, array $orderBy = null)
 * @method OngTipoAporte[]    findAll()
 * @method OngTipoAporte[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OngTipoAporteRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, OngTipoAporte::class);
    }

//    /**
//     * @return OngTipoAporte[] Returns an array of OngTipoAporte objects
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
    public function findOneBySomeField($value): ?OngTipoAporte
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
