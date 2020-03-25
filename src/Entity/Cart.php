<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Service\MoneyFormatter;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     collectionOperations={"post"},
 *     itemOperations={"get", "put"},
 *     normalizationContext={"groups"={"cart:read"}},
 *     denormalizationContext={"groups"={"cart:write"}}
 * )
 * @ORM\Entity(repositoryClass="App\Repository\CartRepository")
 */
class Cart
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Product", cascade={"persist"})
     * @Groups({"cart:read", "cart:write"})
     *
     * @Assert\Count(
     *     max=3,
     *     maxMessage = "You cannot add more than {{ limit }} product to the cart"
     * )
     */
    private $products;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function getTotalPrice(): int
    {
        $sum = 0;
        foreach ($this->getProducts() as $product) {
            $sum += $product->getPrice();
        }

        return $sum;
    }

    /**
     * @Groups({"cart:read"})
     * @SerializedName("totalPrice")
     */
    public function getFormattedTotalPrice(): string
    {
        return MoneyFormatter::pln($this->getTotalPrice());
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->contains($product)) {
            $this->products->removeElement($product);
        }

        return $this;
    }
}
