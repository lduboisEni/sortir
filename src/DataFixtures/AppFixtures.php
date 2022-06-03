<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use App\Entity\City;
use App\Entity\Place;
use App\Entity\State;
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

    private UserPasswordHasherInterface $hasher;
    private Generator $faker;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
        $this->faker = Factory::create('fr_FR');
    }

    //la méthode load ajoute cities, places, trips, users
    public function load(ObjectManager $manager): void
    {

        $this->addCampus($manager);
        $this->addCities($manager);
        $this->addUsers($manager);
        $this->addStates($manager);
        $this->addPlaces($manager);
        //$this->addTrips();


    }

    //méthode pour ajouter des campus
    public function addCampus(ObjectManager $manager)
    {

        //je crée une liste de campus
        $campusList = [
            ['name' => "Rennes"],
            ['name' => "Nantes"],
            ['name' => "Quimper"],
            ['name' => "Niort"],
        ];

        //je boucle sur chaque élément de ma liste pour créer les campus dans ma BDD
        foreach ($campusList as $campus) {

            $newCampus = new Campus();

            $newCampus
                ->setName($campus['name']);

            $manager->persist($newCampus);
        }

        $manager->flush();
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
        for ($i = 0; $i <= 10; $i++) {

        $place = new Place();

        $place
            ->setCity($this->faker->randomElement($cities))
            ->setName($this->faker->randomElement($places))
            ->setStreet($this->faker->streetName)
            ->setLat($this->faker->latitude)
            ->setLongitude($this->faker->longitude);


        $manager->persist($place);
        }

        $manager->flush();

    }

    //méthode pour ajouter des participants
    public function addUsers(ObjectManager $manager)
    {

        $campusList = $manager->getRepository(Campus::class)->findAll();

        for ($i = 0; $i <= 10; $i++) {

            $user = new User();

            $plainPassword = $this->faker->password;
            $hashed = $this->hasher->hashPassword($user, $plainPassword);

            $user
                ->setName($this->faker->lastName)
                ->setFirstname($this->faker->firstName)
                ->setPseudo($this->faker->firstName)
                ->setPhoneNumber($this->faker->phoneNumber)
                ->setEMail($this->faker->email)
                ->setPassWord($hashed)
                ->setActive(1)
                ->setAdmin(0)
                ->setCampus($this->faker->randomElement($campusList));

            $manager->persist($user);
        }

        //Je crée 3 users avec password connu

//        $user = new User();
//
//        $plainPassword = "lea";
//        $hashed = $this->hasher->hashPassword($user, $plainPassword);
//
//        $user
//            ->setName("Dubois")
//            ->setFirstname("Léa")
//            ->setPseudo("Fluff")
//            ->setPhoneNumber($this->faker->phoneNumber)
//            ->setEMail("lea.dubois@gmail.com")
//            ->setPassWord($hashed)
//            ->setActive(1)
//            ->setAdmin(0)
//            ->setCampus($this->faker->randomElement($campusList));
//
//        $manager->persist($user);

        $user = new User();

        $plainPassword = "sylvaine";
        $hashed = $this->hasher->hashPassword($user, $plainPassword);

        $user
            ->setName("Boucard")
            ->setFirstname("Sylvaine")
            ->setPseudo("Sly")
            ->setPhoneNumber($this->faker->phoneNumber)
            ->setEMail("sylvaine.boucard@gmail.com")
            ->setPassWord($hashed)
            ->setActive(1)
            ->setAdmin(0)
            ->setCampus($this->faker->randomElement($campusList));

        $manager->persist($user);

//        $user = new User();
//
//        $plainPassword = "soline";
//        $hashed = $this->hasher->hashPassword($user, $plainPassword);
//
//        $user
//            ->setName("Foret")
//            ->setFirstname("Soline")
//            ->setPseudo("Soso")
//            ->setPhoneNumber($this->faker->phoneNumber)
//            ->setEMail("soline.foret@gmail.com")
//            ->setPassWord($hashed)
//            ->setActive(1)
//            ->setAdmin(0)
//            ->setCampus($this->faker->randomElement($campusList));
//
//        $manager->persist($user);
//
//        $manager->flush();
       }

    public function addStates(ObjectManager $manager)
    {

        //je crée une liste d'état
        $states = [
            ['description' => "Créée"],
            ['description' => "Ouverte"],
            ['description' => "Clôturée"],
            ['description' => "En cours"],
            ['description' => "Passée"],
            ['description' => "Annulée"]
        ];

        //je boucle sur chaque élément de ma liste pour créer les états dans ma BDD
        foreach ($states as $state) {

            $newState = new State();

            $newState->setDescription($state['description']);

            $manager->persist($newState);
        }

        $manager->flush();

    }

    //méthode pour ajouter des sorties
    public function addTrips()
    {

    }

}