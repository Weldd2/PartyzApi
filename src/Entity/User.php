<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use ApiPlatform\Metadata\ApiResource;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_PHONE_NUMBER', fields: ['phoneNumber'])]
#[ApiResource(
	normalizationContext: ['groups' => ['user:read']]
)]
class User implements UserInterface
{
	#[ORM\Id]
	#[ORM\GeneratedValue]
	#[ORM\Column]
	#[Groups(['user:read', 'party:read'])]
	private ?int $id = null;

	#[Assert\Regex('/^\+(?:[0-9] ?){6,14}[0-9]$/')]
	#[ORM\Column(length: 180)]
	#[Groups(['user:read', 'party:read'])]
	private ?string $phoneNumber = null;

	/**
	 * @var list<string> The user roles
	 */
	#[ORM\Column]
	private array $roles = [];

	#[ORM\Column(length: 255)]
	#[Groups(['user:read', 'party:read'])]
	private ?string $firstname = null;

	#[ORM\Column(length: 255, nullable: true)]
	#[Groups(['user:read', 'party:read'])]
	private ?string $lastname = null;

	/**
	 * @var Collection<int, Party>
	 */
	#[ORM\ManyToMany(targetEntity: Party::class, inversedBy: 'members')]
	private Collection $parties;

	/**
	 * @var Collection<int, ShoppingListContribution>
	 */
	#[ORM\OneToMany(targetEntity: ShoppingListContribution::class, mappedBy: 'contributor', orphanRemoval: true)]
	#[Groups(['user:contributions:read'])]
	private Collection $shoppingListContributions;

	/**
	 * @var Collection<int, Party>
	 */
	#[ORM\OneToMany(targetEntity: Party::class, mappedBy: 'owner')]
	private Collection $partiesAsOwner;

	public function __construct()
	{
		$this->parties = new ArrayCollection();
		$this->shoppingListContributions = new ArrayCollection();
		$this->partiesAsOwner = new ArrayCollection();
	}

	public function getId(): ?int
	{
		return $this->id;
	}

	public function getPhoneNumber(): ?string
	{
		return $this->phoneNumber;
	}

	public function setPhoneNumber(string $phoneNumber): static
	{
		$this->phoneNumber = $phoneNumber;

		return $this;
	}

	/**
	 * A visual identifier that represents this user.
	 *
	 * @see UserInterface
	 */
	public function getUserIdentifier(): string
	{
		return (string) $this->phoneNumber;
	}

	/**
	 * @see UserInterface
	 */
	public function getRoles(): array
	{
		$roles = $this->roles;
		// guarantee every user at least has ROLE_USER
		$roles[] = 'ROLE_USER';

		return array_unique($roles);
	}

	/**
	 * @param list<string> $roles
	 */
	public function setRoles(array $roles): static
	{
		$this->roles = $roles;

		return $this;
	}

	/**
	 * @see UserInterface
	 */
	public function eraseCredentials(): void
	{
		// If you store any temporary, sensitive data on the user, clear it here
		// $this->plainPassword = null;
	}

	public function getFirstname(): ?string
	{
		return $this->firstname;
	}

	public function setFirstname(string $firstname): static
	{
		$this->firstname = $firstname;

		return $this;
	}

	public function getLastname(): ?string
	{
		return $this->lastname;
	}

	public function setLastname(?string $lastname): static
	{
		$this->lastname = $lastname;

		return $this;
	}

	/**
	 * @return Collection<int, Party>
	 */
	public function getParties(): Collection
	{
		return $this->parties;
	}

	public function addParty(Party $party): static
	{
		if (!$this->parties->contains($party)) {
			$this->parties->add($party);
		}

		return $this;
	}

	public function removeParty(Party $party): static
	{
		$this->parties->removeElement($party);

		return $this;
	}

	/**
	 * @return Collection<int, ShoppingListContribution>
	 */
	public function getShoppingListContributions(): Collection
	{
		return $this->shoppingListContributions;
	}

	public function addShoppingListContribution(ShoppingListContribution $shoppingListContribution): static
	{
		if (!$this->shoppingListContributions->contains($shoppingListContribution)) {
			$this->shoppingListContributions->add($shoppingListContribution);
			$shoppingListContribution->setContributor($this);
		}

		return $this;
	}

	public function removeShoppingListContribution(ShoppingListContribution $shoppingListContribution): static
	{
		if ($this->shoppingListContributions->removeElement($shoppingListContribution)) {
			// set the owning side to null (unless already changed)
			if ($shoppingListContribution->getContributor() === $this) {
				$shoppingListContribution->setContributor(null);
			}
		}

		return $this;
	}

	/**
	 * @return Collection<int, Party>
	 */
	public function getPartiesAsOwner(): Collection
	{
		return $this->partiesAsOwner;
	}

	public function addPartiesAsOwner(Party $partiesAsOwner): static
	{
		if (!$this->partiesAsOwner->contains($partiesAsOwner)) {
			$this->partiesAsOwner->add($partiesAsOwner);
			$partiesAsOwner->setOwner($this);
		}

		return $this;
	}

	public function removePartiesAsOwner(Party $partiesAsOwner): static
	{
		if ($this->partiesAsOwner->removeElement($partiesAsOwner)) {
			// set the owning side to null (unless already changed)
			if ($partiesAsOwner->getOwner() === $this) {
				$partiesAsOwner->setOwner(null);
			}
		}

		return $this;
	}
}
