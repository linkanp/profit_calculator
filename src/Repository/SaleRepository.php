<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\Sale;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Sale|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sale|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sale[]    findAll()
 * @method Sale[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SaleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sale::class);
    }

    /**
     * @return int $profit Returns  calculated profit
     */
    
    public function calculateProfit(): int
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = '
            SELECT SUM((`sale`.price - `buy`.price)*`sale_batch`.quantity) as profit FROM `sale`
            LEFT JOIN `sale_batch` on `sale`.id = `sale_batch`.sale_id 
            LEFT JOIN `buy` on `sale_batch`.buy_id = `buy`.id
            ';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch();
        $profit = $result['profit'];
        return (int) $profit;
    }
}
