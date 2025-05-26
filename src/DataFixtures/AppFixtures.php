<?php
namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Party;
use App\Entity\ShoppingListItem;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
	public function load(ObjectManager $manager): void
	{
		$faker = Factory::create('fr_FR');

		$party = new Party();
		$party->setTitle("Anniversaire de " . $faker->firstname());
		$party->setAddress(str_replace("\n", " ", $faker->address()));
		$party->setPostalCode($faker->departmentNumber());
		$party->setCity($faker->city());
		$party->setDate($faker->dateTimeBetween('-5 week', '+5 week'));
		$manager->persist($party);

		for ($i = 0; $i < 20; $i++) {
			$nparty = new Party();
			$nparty->setTitle("Anniversaire de " . $faker->firstname());
			$nparty->setAddress($faker->address());
			$nparty->setPostalCode($faker->departmentNumber());
			$nparty->setCity($faker->city());
			$nparty->setDate($faker->dateTimeBetween('-5 week', '+5 week'));
			$manager->persist($nparty);
		}

		for ($i = 0; $i < 24; $i++) {
			$item = new ShoppingListItem();
			$item->setName("item n" . $i);
			$item->setQuantity(6);
			$item->setBroughtQuantity($faker->numberBetween(0, 6));
			$manager->persist($item);
			$party->addShoppingList($item);
			$manager->persist($party);
		}

		// create 20 products! Bam!
		for ($i = 0; $i < 20; $i++) {
			$user = new User();
			$user->setPhoneNumber(str_replace(" ", "", $faker->phoneNumber()));
			$user->setFirstname($faker->firstname());
			$user->setLastname($faker->lastname());
			$user->addParty($party);
			$manager->persist($user);
		}
		$manager->flush();
	}
}