<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\Sale;
use App\Entity\Buy;
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

    public function getProfit()
    {
        return $this->saleRepository->calculateProfit();
    }

    public function handleBuyAction($data)
    {
        $entity = new Buy();
        $entity->setStock($data['quantity']);
        $entity->setItem('Test Item');
        $entity->setQuantity($data['quantity']);
        $entity->setPrice($data['price']);
        //$errors = $this->validator->validate($entity);
    
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }

    public function handleSaleAction($data)
    {
        if($this->buyRepository->findStock() < $data['quantity']){
            throw new Exception('Sale quantity is higher than available stock.');
            return;
        }
        $buyObjects = $this->buyRepository->findByAvailableStock();
        $saleBatch = [];
        $saleQuantity = $data['quantity'];
        foreach($buyObjects as $key => $buyObject){
            if($saleQuantity > 0){
                if($buyObject->getStock() >= $saleQuantity){
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
            $this->createSaleEntry($saleBatch, $data);
            $this->updateBuyEntry($saleBatch, $data);
            $this->entityManager->getConnection()->commit();
        } catch (Exception $e) {
            $this->entityManager->getConnection()->rollBack();
            throw $e;
        }
    }

    private function createSaleEntry(array $saleBatch, array $data)
    {
        if(!empty($saleBatch)){
            foreach($saleBatch as $k => $saleItem){
                $saleEntity = new Sale();
                $saleEntity->setItem('Test Item');
                $saleEntity->setPrice($data['price']);
                $saleEntity->setQuantity($saleItem['quantity']);
                $saleEntity->setBuyId($saleItem['buy_object']->getId());
                $this->entityManager->persist($saleEntity);
                $this->entityManager->flush();
            }
        }
    } 

    private function updateBuyEntry(array $saleBatch, array $data)
    {
        if(!empty($saleBatch)){
            foreach($saleBatch as $k => $saleItem){
                $buyEntity = $saleItem['buy_object'];
                $buyEntity->setStock($buyEntity->getStock() - $saleItem['quantity']);
                $this->entityManager->persist($buyEntity);
                $this->entityManager->flush();
            }
        }
    } 
}