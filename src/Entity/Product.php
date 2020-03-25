<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Service\MoneyFormatter;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\RangeFilter;

/**
 * @ApiResource(
 *     attributes={"pagination_items_per_page"=3},
 *     collectionOperations={"get", "post"},
 *     itemOperations={"get", "delete", "put"},
 *     normalizationContext={"groups"={"product:read"}},
 *     denormalizationContext={"groups"={"product:write"}}
 * )
 *
 * @ApiFilter(SearchFilter::class, properties={"title": "partial"})
 * @ApiFilter(RangeFilter::class, properties={"price"})
 *
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 */
class Product
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"product:read", "cart:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"product:read", "product:write", "cart:read"})
     * @Assert\NotBlank()
     * @Assert\Length(
     *     min=2,
     *     max=255,
     *     maxMessage="Product description"
     * )
     */
    private $title;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"product:write"})
     * @Assert\NotBlank()
     * @Assert\Positive()
     */
    private $price;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    public function __construct()
    {
        $this->created_at = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

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

    /**
     * @Groups({"product:read", "cart:read"})
     * @SerializedName("price")
     */
    public function getFormattedPrice(): string
    {
        return MoneyFormatter::pln($this->getPrice());
    }

    /**
     * @Groups({"product:read"})
     */
    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }
}
