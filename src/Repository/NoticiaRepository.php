<?php

namespace App\Repository;

use App\Entity\Noticia;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Noticia|null find($id, $lockMode = null, $lockVersion = null)
 * @method Noticia|null findOneBy(array $criteria, array $orderBy = null)
 * @method Noticia[]    findAll()
 * @method Noticia[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NoticiaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Noticia::class);
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
    public function findOneBySomeField($value): ?Noticias
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function findUltNoticiasByTematicas($tematicas_id)
    {
        return $this->createQueryBuilder('n')
        ->join('n.tematicas', 't',  'WITH', 't.id=:tematicas_id')       
        ->andWhere('n.publicado=1')
        ->setParameter('tematicas_id',$tematicas_id)
        ->setMaxResults(3)
        ->getQuery()
        ->getResult();
    }

    public function findNoticiaByTematica($tematica)
    {
        return $this->createQueryBuilder('n')
        ->join('n.tematicas', 't',  'WITH', 't.id = :tematica')       
        ->andWhere('n.publicado=1')
        ->orderBy('n.fechaCreacion','DESC')
        ->setParameter('tematica',$tematica);
    }
    

   
}
