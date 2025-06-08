<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ShoppingListItemRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\OpenApi\Model\Operation;
use ApiPlatform\Metadata\Link;
use ApiPlatform\OpenApi\Model\Parameter;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ShoppingListItemRepository::class)]
#[ApiResource(
	normalizationContext: ['groups' => ['party:read']]
)]
#[ApiResource(
	uriTemplate: '/parties/{id}/shopping_list_items',
	uriVariables: [
		'id' => new Link(
			fromClass: Party::class,
			fromProperty: 'shoppingList'
		)
	],
	normalizationContext: ['groups' => ['party:shoppingListItem:read']],
	operations: [new GetCollection()],
	openapi: new Operation(
		tags: ['Party'],
		summary: 'Get shopping list items for a specific party',
		description: 'Retrieves the collection of ShoppingListItem resources associated with a Party.',
		parameters: [
			new Parameter(
				name: 'id',
				in: 'path',
				description: 'Party identifier',
				required: true,
				schema: ['type' => 'integer']
			)
		]
	)
)]
class ShoppingListItem
{
	#[ORM\Id]
	#[ORM\GeneratedValue]
	#[ORM\Column]
	#[Groups(['party:read', 'party:shoppingListItem:read'])]
	private ?int $id = null;

	#[ORM\Column(length: 255)]
	#[Groups(['party:read', 'user:contributions:read', 'party:shoppingListItem:read'])]
	private ?string $name = null;

	#[ORM\Column]
	#[Groups(['party:read', 'party:shoppingListItem:read'])]
	private ?int $quantity = null;

	#[ORM\ManyToOne(inversedBy: 'shoppingList')]
	#[ORM\JoinColumn(nullable: false)]
	private ?Party $party = null;

	#[ORM\Column]
	#[Groups(['party:read', 'party:shoppingListItem:read'])]
	private ?int $broughtQuantity = null;

	/**
	 * @var Collection<int, ShoppingListContribution>
	 */
	#[ORM\OneToMany(targetEntity: ShoppingListContribution::class, mappedBy: 'shoppingListItem', orphanRemoval: true)]
	private Collection $contributions;

	public function __construct()
	{
		$this->contributions = new ArrayCollection();
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

	public function getQuantity(): ?int
	{
		return $this->quantity;
	}

	public function setQuantity(int $quantity): static
	{
		$this->quantity = $quantity;

		return $this;
	}

	public function getParty(): ?Party
	{
		return $this->party;
	}

	public function setParty(?Party $party): static
	{
		$this->party = $party;

		return $this;
	}

	public function getBroughtQuantity(): ?int
	{
		return $this->broughtQuantity;
	}

	public function setBroughtQuantity(int $broughtQuantity): static
	{
		$this->broughtQuantity = $broughtQuantity;

		return $this;
	}

	/**
	 * @return Collection<int, ShoppingListContribution>
	 */
	public function getContributions(): Collection
	{
		return $this->contributions;
	}

	public function addContribution(ShoppingListContribution $contribution): static
	{
		if (!$this->contributions->contains($contribution)) {
			$this->contributions->add($contribution);
			$contribution->setShoppingListItem($this);
		}

		return $this;
	}

	public function removeContribution(ShoppingListContribution $contribution): static
	{
		if ($this->contributions->removeElement($contribution)) {
			// set the owning side to null (unless already changed)
			if ($contribution->getShoppingListItem() === $this) {
				$contribution->setShoppingListItem(null);
			}
		}

		return $this;
	}
}
