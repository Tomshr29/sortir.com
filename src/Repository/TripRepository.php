<?php

namespace App\Repository;

use App\Entity\Trip;
use App\Model\SearchData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Trip>
 *
 * @method Trip|null find($id, $lockMode = null, $lockVersion = null)
 * @method Trip|null findOneBy(array $criteria, array $orderBy = null)
 * @method Trip[]    findAll()
 * @method Trip[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TripRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Trip::class);
    }

//    /**
//     * @return Trip[] Returns an array of Trip objects
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

//    public function findOneBySomeField($value): ?Trip
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    public function findBySearch(SearchData $searchData): PaginationInterface
    {
       $data = $this->createQueryBuilder('p')
           ->where('p.state LIKE :state')
           ->setParameter('state', '%STATE PUBLISHED%')
           ->addOrderBy('p.createdAt', 'DESC');

       if(!empty($searchData->query)){
           $data = $data
               ->andWhere('p.title LIKE :query')
               ->setParameter('query', "%{$searchData->query}%");
       }

       $data = $data
           ->getQuery()
           ->getResult();

       $trip = $this->paginatorInterface->paginate($data, $searchData->page, 9);

       return $trip;
    }

    /**
     * @throws \Exception
     */
    public function getByDate(\DateTime $date){

        $from = new \DateTime($date->format("d-m-Y")." 00:00:00");
        $to = new \DateTime($date->format("d-m-Y"." 23:59:59"));

        $queryBuilder = $this->createQueryBuilder("e");
        $queryBuilder
            ->andWhere('e.date BETWEEN :from AND :to')
            ->setParameter('from', $from)
            ->setParameter('to', $to);

        $result = $queryBuilder->getQuery()->getResult();

        return $result;


    }
}

