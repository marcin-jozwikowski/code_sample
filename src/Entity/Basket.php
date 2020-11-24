<?php

namespace App\Entity;

use App\Repository\BasketRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BasketRepository::class)
 */
class Basket
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $label;

    /**
     * @ORM\OneToMany(targetEntity=BasketProduct::class, mappedBy="basket", orphanRemoval=true)
     */
    private $basketProducts;

    public function __construct()
    {
        $this->basketProducts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return Collection|BasketProduct[]
     */
    public function getBasketProducts(): Collection
    {
        return $this->basketProducts;
    }

    public function addBasketProduct(BasketProduct $basketProduct): self
    {
        if (!$this->basketProducts->contains($basketProduct)) {
            $this->basketProducts[] = $basketProduct;
            $basketProduct->setBasket($this);
        }

        return $this;
    }

    public function removeBasketProduct(BasketProduct $basketProduct): self
    {
        if ($this->basketProducts->removeElement($basketProduct)) {
            // set the owning side to null (unless already changed)
            if ($basketProduct->getBasket() === $this) {
                $basketProduct->setBasket(null);
            }
        }

        return $this;
    }
}
