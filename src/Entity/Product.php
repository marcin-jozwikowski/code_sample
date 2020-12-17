<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"all", "index"})
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=64)
     *
     * @Assert\Length(min="3", max="64")
     * @Assert\NotBlank()
     * @Groups({"all", "index", "get", "update", "post"})
     */
    private ?string $name;

    /**
     * @ORM\Column(type="integer")
     *
     * @Assert\GreaterThan(0)
     * @Assert\NotBlank()
     * @Groups({"all", "get", "update", "post"})
     */
    private ?int $quantity;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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
