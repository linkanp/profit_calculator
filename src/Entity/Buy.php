<?php
declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BuyRepository")
 */
class Buy
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $item;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank
     * @Assert\Type("int")
     */
    private $quantity;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank
     * @Assert\Type("int")
     */
    private $price;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank
     * @Assert\Type("int")
     */
    private $stock;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\SaleBatch", mappedBy="buy_id")
     */
    private $sales;

    public function __construct()
    {
        $this->sales = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getItem(): ?string
    {
        return $this->item;
    }

    public function setItem(string $item): self
    {
        $this->item = $item;

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

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): self
    {
        $this->stock = $stock;

        return $this;
    }

    /**
     * @return Collection|SaleBatch[]
     */
    public function getSales(): Collection
    {
        return $this->sales;
    }

    public function addSale(SaleBatch $sale): self
    {
        if (!$this->sales->contains($sale)) {
            $this->sales[] = $sale;
            $sale->setBuyId($this);
        }

        return $this;
    }

    public function removeSale(SaleBatch $sale): self
    {
        if ($this->sales->contains($sale)) {
            $this->sales->removeElement($sale);
            // set the owning side to null (unless already changed)
            if ($sale->getBuyId() === $this) {
                $sale->setBuyId(null);
            }
        }

        return $this;
    }
}
