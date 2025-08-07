<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
#[ApiResource]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column]
    private ?float $price = null;

    #[ORM\Column(nullable: true)]
    private ?float $rating = null;

    #[ORM\Column]
    private ?bool $inStock = null;

    /**
     * @var Collection<int, BasketEntry>
     */
    #[ORM\OneToMany(targetEntity: BasketEntry::class, mappedBy: 'article')]
    private Collection $basketEntries;

    public function __construct()
    {
        $this->basketEntries = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getRating(): ?float
    {
        return $this->rating;
    }

    public function setRating(?float $rating): static
    {
        $this->rating = $rating;

        return $this;
    }

    public function isInStock(): ?bool
    {
        return $this->inStock;
    }

    public function setInStock(bool $inStock): static
    {
        $this->inStock = $inStock;

        return $this;
    }

    /**
     * @return Collection<int, BasketEntry>
     */
    public function getBasketEntries(): Collection
    {
        return $this->basketEntries;
    }

    public function addBasketEntry(BasketEntry $basketEntry): static
    {
        if (!$this->basketEntries->contains($basketEntry)) {
            $this->basketEntries->add($basketEntry);
            $basketEntry->setArticle($this);
        }

        return $this;
    }

    public function removeBasketEntry(BasketEntry $basketEntry): static
    {
        if ($this->basketEntries->removeElement($basketEntry)) {
            // set the owning side to null (unless already changed)
            if ($basketEntry->getArticle() === $this) {
                $basketEntry->setArticle(null);
            }
        }

        return $this;
    }
}
