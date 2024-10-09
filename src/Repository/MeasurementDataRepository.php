<?php

namespace App\Repository;

use App\Entity\MeasurementData;
use App\Entity\Location;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MeasurementData>
 *
 * @method MeasurementData|null find($id, $lockMode = null, $lockVersion = null)
 * @method MeasurementData|null findOneBy(array $criteria, array $orderBy = null)
 * @method MeasurementData[]    findAll()
 * @method MeasurementData[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MeasurementDataRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MeasurementData::class);
    }

    public function findByLocation(Location $location)
    {
        $qb = $this->createQueryBuilder('m');
        $qb->where('m.location = :location')
            ->setParameter('location', $location)
            ->andWhere('m.date > :now')
            ->setParameter('now', date('Y-m-d'));

        $query = $qb->getQuery();
        $result = $query->getResult();
        return $result;
    }


//    /**
//     * @return MeasurementData[] Returns an array of MeasurementData objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?MeasurementData
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
