<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use App\Entity\City;
use App\Entity\Place;
use App\Entity\Trip;
use App\Entity\User;
use App\Repository\CampusRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

use Faker\Generator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $hasher;
    private $faker;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
        $this->faker = Factory::create('fr_FR');


    }

    //la méthode load ajoute cities, places, trips, users
    public function load(ObjectManager $manager): void
    {

        //$this->addCities($manager);
        //$this->addPlaces($manager);
//        $this->addTrips();
        $this->addUsers($manager);

    }

    //méthode pour ajouter des villes
    public function addCities(ObjectManager $manager)
    {

        //je crée une liste de villes
        $cities = [
            ['name' => "Rennes", 'cp' => "35000"],
            ['name' => "Nantes", 'cp' => "44000"],
            ['name' => "Niort", 'cp' => "79000"],
            ['name' => "Quimper", 'cp' => "29000"],
        ];

        //je boucle sur chaque élément de ma liste pour créer les villes dans ma BDD
        foreach ($cities as $city) {
            $newCity = new City();

            $newCity
                ->setName($city['name'])
                ->setCp($city['cp']);

            $manager->persist($newCity);
        }

        $manager->flush();

    }

    //méthode pour ajouter des lieux
    public function addPlaces(ObjectManager $manager)
    {
        //je récupère la liste de ville de ma BDD
        $cities = $manager->getRepository(City::class)->findAll();

        //je crée une liste de nom de lieux
        $places = ['Cinema', 'Bowling', 'Patinoire', 'Bar'];

        //je crée 10 lieux
        //for ($i = 0; $i <= 10; $i++) {

        $place = new Place();

        $place
            ->setCity($this->faker->randomElement($cities))
            ->setName($this->faker->randomElement($places));


        $manager->persist($place);
        //}

        $manager->flush();

    }

    //méthode pour ajouter des participants
    public function addUsers(ObjectManager $manager)
    {
        $campusList = $manager->getRepository(Campus::class)->findAll();

        for ($i = 0; $i <= 10; $i++) {

            $user = new User();

            $user
                ->setName($this->faker->lastName)
                ->setFirstname($this->faker->firstName)
                ->setPseudo($this->faker->firstName)
                ->setPhoneNumber($this->faker->phoneNumber)
                ->setEMail($this->faker->email)
                ->setPassWord($this->faker->word)
                ->setActive(1)
                ->setAdmin(0)
                ->setCampus($this->faker->randomElement($campusList));

            $manager->persist($user);
        }

        $manager->flush();
    }

    //méthode pour ajouter des sorties
    public function addTrips()
    {

    }

}