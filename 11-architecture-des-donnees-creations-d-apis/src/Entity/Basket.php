<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\BasketRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BasketRepository::class)]
#[ApiResource]
class Basket
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $status = null;

    #[ORM\Column]
    private ?\DateTime $openedAt = null;

    /**
     * @var Collection<int, BasketEntry>
     */
    #[ORM\OneToMany(targetEntity: BasketEntry::class, mappedBy: 'basket', orphanRemoval: true)]
    private Collection $basketEntries;

    public function __construct()
    {
        $this->basketEntries = new ArrayCollection();
        $this->openedAt = new \DateTime();
        $this->status = 'pending';
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getOpenedAt(): ?\DateTime
    {
        return $this->openedAt;
    }

    public function setOpenedAt(\DateTime $openedAt): static
    {
        $this->openedAt = $openedAt;

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
            $basketEntry->setBasket($this);
        }

        return $this;
    }

    public function removeBasketEntry(BasketEntry $basketEntry): static
    {
        if ($this->basketEntries->removeElement($basketEntry)) {
            // set the owning side to null (unless already changed)
            if ($basketEntry->getBasket() === $this) {
                $basketEntry->setBasket(null);
            }
        }

        return $this;
    }
}
