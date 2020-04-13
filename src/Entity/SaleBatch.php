<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SaleBatchRepository")
 */
class SaleBatch
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Buy", inversedBy="sales")
     * @ORM\JoinColumn(nullable=false)
     */
    private $buy;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Sale", inversedBy="sales")
     * @ORM\JoinColumn(nullable=false)
     */
    private $sale;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantity;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBuy(): ?Buy
    {
        return $this->buy;
    }

    public function setBuy(?Buy $buy): self
    {
        $this->buy = $buy;

        return $this;
    }

    public function getSale(): ?Sale
    {
        return $this->sale;
    }

    public function setSale(?Sale $sale): self
    {
        $this->sale = $sale;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }
}
