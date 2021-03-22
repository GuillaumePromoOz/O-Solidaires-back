<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Department;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use App\DataFixtures\Provider\OsolidaireProvider;

class AppFixtures extends Fixture
{
    const NB_DEPARTMENTS = 5;
    const NB_CATEGORIES = 5;
    const NB_USERS = 10;
    const NB_PROPOSITIONS = 2 * self::NB_USERS;
    const NB_REQUESTS = 2 * self::NB_USERS;


    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        $faker->seed('osolidaires');
        $faker->addProvider(new OsolidaireProvider());

        // ---- DEPARTEMENT ---- //
        $departments = $faker->getDepartments();
        $departmentsList = [];
        foreach( $departments as $content){
            $department = new Department();

            $department->setName($content);

            $department->setCreatedAt(new \DateTime());
            // On stocke la personne pour usage ultérieur
            $departementsList[] = $department;

            $manager->persist($department);
        }
        

        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
