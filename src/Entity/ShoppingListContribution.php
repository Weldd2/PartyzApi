<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ShoppingListContributionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ShoppingListContributionRepository::class)]
#[ApiResource]
class ShoppingListContribution
{
	#[ORM\Id]
	#[ORM\GeneratedValue]
	#[ORM\Column]
	private ?int $id = null;

	#[ORM\Column]
	private ?int $quantity = null;

	#[ORM\ManyToOne(inversedBy: 'contributions')]
	#[ORM\JoinColumn(nullable: false)]
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
