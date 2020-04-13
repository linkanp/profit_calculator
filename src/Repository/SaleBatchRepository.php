<?php

namespace App\Repository;

use App\Entity\SaleBatch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SaleBatch|null find($id, $lockMode = null, $lockVersion = null)
 * @method SaleBatch|null findOneBy(array $criteria, array $orderBy = null)
 * @method SaleBatch[]    findAll()
 * @method SaleBatch[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SaleBatchRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SaleBatch::class);
    }
}
