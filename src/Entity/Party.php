<?php

namespace App\Entity;

use ApiPlatform\Metadata\Link;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\PartyRepository;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\OpenApi\Model\Operation;
use ApiPlatform\OpenApi\Model\Parameter;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;

#[ORM\Entity(repositoryClass: PartyRepository::class)]
#[ApiResource(
	normalizationContext: ['groups' => ['party:read']]
)]
#[ApiResource(
	uriTemplate: '/users/{id}/parties',
	uriVariables: [
		'id' => new Link(
			fromClass: User::class,
			fromProperty: 'parties'
		)
	],
	normalizationContext: ['groups' => ['user:party:read']],
	operations: [new GetCollection()],
	openapi: new Operation(
		tags: ['User'],
		summary: 'Get parties for a specific user',
		description: 'Retrieves the Parties where the User is a member',
		parameters: [
			new Parameter(
				name: 'id',
				in: 'path',
				description: 'User identifier',
				required: true,
				schema: ['type' => 'integer']
			)
		]
	)
)]
#[ApiResource(
	uriTemplate: '/users/{id}/partiesAsOwner',
	uriVariables: [
		'id' => new Link(
			fromClass: User::class,
			fromProperty: 'partiesAsOwner'
		)
	],
	normalizationContext: ['groups' => ['user:party:read']],
	operations: [new GetCollection()],
	openapi: new Operation(
		tags: ['User'],
		summary: 'Get parties for a specific user',
		description: 'Retrieves the collection of Party resources associated with a User.'
	)
)]
class Party
{
	#[ORM\Id]
	#[ORM\GeneratedValue]
	#[ORM\Column]
	#[Groups(['user:party:read', 'party:read'])]
	private ?int $id = null;

	#[ORM\Column(length: 255)]
	#[Groups(['user:party:read', 'party:read'])]
	private ?string $title = null;

	#[ORM\Column(length: 255)]
	#[Groups(['user:party:read', 'party:read'])]
	private ?string $address = null;

	#[ORM\Column(length: 255)]
	private ?string $postalCode = null;

	#[ORM\Column(length: 255)]
	private ?string $city = null;

	#[ORM\Column]
	#[Groups(['user:party:read', 'party:read'])]
	private ?\DateTime $date = null;

	/**
	 * @var Collection<int, User>
	 */
	#[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'parties')]
	#[Groups(['party:read'])]
	#[MaxDepth(1)]
	private Collection $members;

	/**
	 * @var Collection<int, ShoppingListItem>
	 */
	#[ORM\OneToMany(targetEntity: ShoppingListItem::class, mappedBy: 'party', orphanRemoval: true)]
	#[Groups(['party:read', 'party:shoppingListItem:read'])]
	private Collection $shoppingList;

	#[ORM\ManyToOne(inversedBy: 'partiesAsOwner')]
	#[ORM\JoinColumn(nullable: false)]
	#[Groups(['user:party:read', 'party:read'])]
	private ?User $owner = null;

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

	public function getOwner(): ?User
	{
		return $this->owner;
	}

	public function setOwner(?User $owner): static
	{
		$this->owner = $owner;

		return $this;
	}
}
