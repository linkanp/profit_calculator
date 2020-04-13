<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\Buy;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Buy|null find($id, $lockMode = null, $lockVersion = null)
 * @method Buy|null findOneBy(array $criteria, array $orderBy = null)
 * @method Buy[]    findAll()
 * @method Buy[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BuyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Buy::class);
    }

    /**
     * @return Buy[] Returns an array of Buy objects
     */
    
    public function findByAvailableStock(): array
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.stock > :val')
            ->setParameter('val', 0)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return int $stock Returns an array with of stock count
     */
    
    public function findStock(): int
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = '
            SELECT SUM(stock) as stock_count FROM `buy`
            ';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch();
        $stock = $result['stock_count'];
        return (int) $stock;
    }
}
