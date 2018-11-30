<?php

namespace App\Repository;

use App\Entity\Programa;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Programa|null find($id, $lockMode = null, $lockVersion = null)
 * @method Programa|null findOneBy(array $criteria, array $orderBy = null)
 * @method Programa[]    findAll()
 * @method Programa[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProgramaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Programa::class);
    }

//    /**
//     * @return Tematicas[] Returns an array of Tematicas objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Tematicas
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findUltProgramasByTematicas($tematicas_id)
    {
        return $this->createQueryBuilder('p')
        ->join('p.tematicas', 't',  'WITH', 't.id=:tematicas_id')       
        ->andWhere('p.publicado=1')
        ->setParameter('tematicas_id',$tematicas_id)
        ->setMaxResults(3)
        ->getQuery()
        ->getResult();
    }

    public function findProgramasByTematica($tematica)
    {
        return $this->createQueryBuilder('p')
        ->join('p.tematicas', 't',  'WITH', 't.id = :tematica')       
        ->where('p.publicado=1')
        ->orderBy('p.fechaCreacion','DESC')
        ->setParameter('tematica',$tematica);
       
    }

}
