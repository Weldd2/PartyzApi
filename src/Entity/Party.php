<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\PartyRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PartyRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['party:read']],
)]
class Party
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["party:read"])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(["party:read"])]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    #[Groups(["party:read"])]
    private ?string $address = null;

    #[ORM\Column(length: 255)]
    #[Groups(["party:read"])]
    private ?string $postalCode = null;

    #[ORM\Column(length: 255)]
    #[Groups(["party:read"])]
    private ?string $city = null;

    #[ORM\Column]
    #[Groups(["party:read"])]
    private ?\DateTime $date = null;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'parties')]
    #[Groups(["party:read"])]
    private Collection $members;

    /**
     * @var Collection<int, ShoppingListItem>
     */
    #[ORM\OneToMany(targetEntity: ShoppingListItem::class, mappedBy: 'party', orphanRemoval: true)]
    #[Groups(["party:read"])]
    private Collection $shoppingList;

    public function __construct()
    {
        $this->members = new ArrayCollection();
        $this->shoppingList = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(string $postalCode): static
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    public function setDate(\DateTime $date): static
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getMembers(): Collection
    {
        return $this->members;
    }

    public function addMember(User $member): static
    {
        if (!$this->members->contains($member)) {
            $this->members->add($member);
            $member->addParty($this);
        }

        return $this;
    }

    public function removeMember(User $member): static
    {
        if ($this->members->removeElement($member)) {
            $member->removeParty($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, ShoppingListItem>
     */
    public function getShoppingList(): Collection
    {
        return $this->shoppingList;
    }

    public function addShoppingList(ShoppingListItem $shoppingList): static
    {
        if (!$this->shoppingList->contains($shoppingList)) {
            $this->shoppingList->add($shoppingList);
            $shoppingList->setParty($this);
        }

        return $this;
    }

    public function removeShoppingList(ShoppingListItem $shoppingList): static
    {
        if ($this->shoppingList->removeElement($shoppingList)) {
            // set the owning side to null (unless already changed)
            if ($shoppingList->getParty() === $this) {
                $shoppingList->setParty(null);
            }
        }

        return $this;
    }
}
