<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Book>
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

//    /**
//     * @return Book[] Returns an array of Book objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Book
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
    public function getNbrBook(): int
    {
        $qb = $this->createQueryBuilder('b')
            ->select('COUNT(b.id)');
        return (int) $qb->getQuery()->getResult();
    }
    public function getNbrBooks(){
        $em = $this->getEntityManager();
        $query = $em->createQuery('SELECT COUNT(b.id) FROM App\Entity\Book b');
        return $query->getSingleScalarResult();
    }
    public function getBooksByAuthor($authorId){
        return $this->createQueryBuilder('b')
            ->andWhere('b.author = :authorId')
            ->setParameter('authorId', $authorId)
            ->getQuery()
            ->getResult();
    }
    public function getBookByAuthor($authorId){
        $em = $this->getEntityManager();
        $query = $em->createQuery('SELECT b FROM App\Entity\Book b WHERE b.author = :authorId')
            ->setParameter('authorId', $authorId);
        return $query->getResult();
    }
}
    
