<?php

namespace App\Entity;

use App\Entity\User;
use ApiPlatform\Metadata\Link;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\OpenApi\Model\Operation;
use ApiPlatform\OpenApi\Model\Parameter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Repository\ShoppingListContributionRepository;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ShoppingListContributionRepository::class)]
#[ApiResource]
#[ApiResource(
	uriTemplate: '/users/{id}/contributions',
	uriVariables: [
		'id' => new Link(
			fromClass: User::class,
			fromProperty: 'shoppingListContributions'
		)
	],
	normalizationContext: ['groups' => ['user:contributions:read']],
	operations: [new GetCollection()],
	openapi: new Operation(
		tags: ['User'],
		summary: 'Get contributions for a specific user',
		description: 'Retrieves the collection of Contributions resources associated with a User. Can be filtered by party using ?shoppingListItem.party.id=<partyId>',
		parameters: [
			new Parameter(
				name: 'id',
				in: 'path',
				description: 'User identifier',
				required: true,
				schema: ['type' => 'integer']
			),
			new Parameter(
				name: 'shoppingListItem.party.id',
				in: 'query',
				description: 'Filter contributions by party ID',
				required: false,
				schema: ['type' => 'integer']
			)
		]
	)
)]
#[ApiFilter(SearchFilter::class, properties: ['shoppingListItem.party.id' => 'exact'])]
class ShoppingListContribution
{
	#[ORM\Id]
	#[ORM\GeneratedValue]
	#[ORM\Column]
	#[Groups(['user:contributions:read'])]
	private ?int $id = null;

	#[ORM\Column]
	#[Groups(['user:contributions:read'])]
	private ?int $quantity = null;

	#[ORM\ManyToOne(inversedBy: 'contributions')]
	#[ORM\JoinColumn(nullable: false)]
	#[Groups(['user:contributions:read'])]
	private ?ShoppingListItem $shoppingListItem = null;

	#[ORM\ManyToOne(inversedBy: 'shoppingListContributions')]
	#[ORM\JoinColumn(nullable: false)]
	private ?User $contributor = null;

	public function getId(): ?int
	{
		return $this->id;
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

	public function getShoppingListItem(): ?ShoppingListItem
	{
		return $this->shoppingListItem;
	}

	public function setShoppingListItem(?ShoppingListItem $shoppingListItem): static
	{
		$this->shoppingListItem = $shoppingListItem;

		return $this;
	}

	public function getContributor(): ?User
	{
		return $this->contributor;
	}

	public function setContributor(?User $contributor): static
	{
		$this->contributor = $contributor;

		return $this;
	}
}
