<?php

namespace App\Repository;

use App\Entity\NoticiaComentario;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method NoticiaComentario|null find($id, $lockMode = null, $lockVersion = null)
 * @method NoticiaComentario|null findOneBy(array $criteria, array $orderBy = null)
 * @method NoticiaComentario[]    findAll()
 * @method NoticiaComentario[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NoticiaComentarioRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, NoticiaComentario::class);
    }

//    /**
//     * @return NoticiaComentario[] Returns an array of NoticiaComentario objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('n.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?NoticiaComentario
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
