<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\Sale;
use App\Entity\Buy;
use App\Entity\SaleBatch;
use App\Repository\BuyRepository;
use App\Repository\SaleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Config\Definition\Exception\Exception;

class CalculatorService
{
    private $buyRepository;

    private $saleRepository;
    
    private $entityManager;
    
    public function __construct(
        BuyRepository $buyRepository,
        SaleRepository $saleRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->buyRepository = $buyRepository;
        $this->saleRepository = $saleRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * Calculate profit for all buy and sale data
     *
     * @return int Calculated Profit
     *
     */
    public function getProfit()
    {
        return $this->saleRepository->calculateProfit();
    }

    /**
     * Logic for handle Buy or Sale action
     *
     * @param string $action buy/sale action
     * @param array $data Data to store in db
     *
     */
    public function handleAction($action, $data)
    {
        switch ($action) {
            case 'sale':
                $this->handleSaleAction($data);
            break;
            case 'buy':
                $this->handleBuyAction($data);
            break;
        }
    }
    /**
     * Handle the Buy Action to store data in DB
     *
     * @param array $data Data to store in db
     *
     */
    private function handleBuyAction($data)
    {
        $entity = new Buy();
        $entity->setStock($data['quantity']);
        $entity->setItem('Test Item');
        $entity->setQuantity($data['quantity']);
        $entity->setPrice($data['price']);
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }

    /**
     * Handle the Sale Action - Check Available stock and FIFO Sale order
     *
     * @param array $data Data to store in db
     * @throws Exception If Sale quantity is higher than available stock
     */
    private function handleSaleAction($data)
    {
        if ($this->buyRepository->findStock() < $data['quantity']) {
            throw new Exception('Sale quantity is higher than available stock.');
            return;
        }
        $buyObjects = $this->buyRepository->findByAvailableStock();
        $saleBatch = [];
        $saleQuantity = $data['quantity'];
        foreach ($buyObjects as $key => $buyObject) {
            if ($saleQuantity > 0) {
                if ($buyObject->getStock() >= $saleQuantity) {
                    $saleBatch[] = ['buy_object' => $buyObject,'quantity' => $saleQuantity];
                    $saleQuantity = 0;
                } else {
                    $saleBatch[] = ['buy_object' => $buyObject,'quantity' => $buyObject->getStock()];
                    $saleQuantity -= $buyObject->getStock();
                }
            } else {
                break;
            }
        }
        $this->entityManager->getConnection()->beginTransaction();
        try {
            $saleEntity = $this->createSaleEntry($data);
            $this->createSaleBatchEntry($saleBatch, $data, $saleEntity);
            $this->updateBuyEntry($saleBatch);
            $this->entityManager->getConnection()->commit();
        } catch (Exception $e) {
            $this->entityManager->getConnection()->rollBack();
            throw $e;
        }
    }

    /**
     * Store the sale data in DB
     *
     * @param array $data Data to store in db
     *
     */
    private function createSaleEntry($data)
    {
        $entity = new Sale();
        $entity->setItem('Test Item');
        $entity->setQuantity($data['quantity']);
        $entity->setPrice($data['price']);
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
        return $entity;
    }
    /**
     * Store the sale data in DB
     *
     * @param array $data Data to store in db
     *
     */
    private function createSaleBatchEntry(array $saleBatch, array $data, Sale $saleEntity)
    {
        if (!empty($saleBatch)) {
            foreach ($saleBatch as $k => $saleItem) {
                $saleBatchEntity = new SaleBatch();
                $saleBatchEntity->setQuantity($saleItem['quantity']);
                $saleBatchEntity->setBuy($saleItem['buy_object']);
                $saleBatchEntity->setSale($saleEntity);
                
                $this->entityManager->persist($saleBatchEntity);
                $this->entityManager->flush();
            }
        }
    }

    /**
     * Update stock after sale
     *
     * @param array $saleBatch rows that will be updated
     *
     */
    private function updateBuyEntry(array $saleBatch)
    {
        if (!empty($saleBatch)) {
            foreach ($saleBatch as $k => $saleItem) {
                $buyEntity = $saleItem['buy_object'];
                $buyEntity->setStock($buyEntity->getStock() - $saleItem['quantity']);
                $this->entityManager->persist($buyEntity);
                $this->entityManager->flush();
            }
        }
    }
}
