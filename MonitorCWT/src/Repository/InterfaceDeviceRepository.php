<?php

namespace App\Repository;

use App\Entity\InterfaceDevice;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method InterfaceDevice|null find($id, $lockMode = null, $lockVersion = null)
 * @method InterfaceDevice|null findOneBy(array $criteria, array $orderBy = null)
 * @method InterfaceDevice[]    findAll()
 * @method InterfaceDevice[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InterfaceDeviceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InterfaceDevice::class);
    }

    // /**
    //  * @return InterfaceDevice[] Returns an array of InterfaceDevice objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?InterfaceDevice
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
