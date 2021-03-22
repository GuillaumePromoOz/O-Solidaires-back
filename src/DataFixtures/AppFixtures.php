<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Category;
use App\Entity\Department;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use App\DataFixtures\Provider\OsolidaireProvider;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    // Password encoder
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        
        $this->passwordEncoder = $passwordEncoder;
        
    }
    
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

        //--- CATEGORY ---//
        $categories = $faker->getCategories();
        $categoriesList = [];
        foreach( $categories as $content){
            
            $categorie = new Category();

            $categorie->setName($content);

            $categorie->setCreatedAt(new \DateTime());
            
            $categoriesList[] = $categorie;

            $manager->persist($categorie);
        }
        
        //--- BENEFICIARY ---//

        $beneficiariesList = [];
        for ($i=1; $i <= self::NB_USERS; $i++) {
            
            $user = new User();
            $plainPassword = 'Az123456';
            $encoded = $this->passwordEncoder->encodePassword($user,$plainPassword);
            $user->setPassword($encoded);
            $user->setLastname($faker->lastName());
            $user->setFirstname($faker->firstName());
            
            
            $user->setEmail(strtolower(preg_replace('/\s+/', '', $user->getLastname())).'@gmail.com');
            $user->setRoles(['ROLE_BENEFICIARY']);

            $randomDepartement = $departementsList[array_rand($departementsList)];
            $user->setDepartment($randomDepartement);

            $user->setCreatedAt(new \DateTime());
            
            $beneficiariesList[] = $user;

            $manager->persist($user);
        }

        //--- VOLUNTEER ---//

        $volunteersList = [];
        for ($i=1; $i <= self::NB_USERS; $i++) {
            
            $user = new User();
            $plainPassword = 'Az123456';
            $encoded = $this->passwordEncoder->encodePassword($user,$plainPassword);
            $user->setPassword($encoded);
            $user->setLastname($faker->lastName());
            $user->setFirstname($faker->firstName());
            
            
            $user->setEmail(strtolower(preg_replace('/\s+/', '', $user->getLastname())).'@gmail.com');
            $user->setRoles(['ROLE_VOLUNTEER']);

            $randomDepartement = $departementsList[array_rand($departementsList)];
            $user->setDepartment($randomDepartement);

            $user->setCreatedAt(new \DateTime());
            // On stocke la personne pour usage ultérieur
            $volunteersList[] = $user;

            $manager->persist($user);
        }

        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
