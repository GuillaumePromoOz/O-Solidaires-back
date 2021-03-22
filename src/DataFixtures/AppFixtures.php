<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Request;
use App\Entity\Category;
use App\Entity\Department;
use App\Entity\Proposition;
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

        //--- ADMIN ---//

        $adminsList = [];
        for ($i=1; $i <= 2; $i++) {
            
            $user = new User();
            $plainPassword = 'Az123456';
            $encoded = $this->passwordEncoder->encodePassword($user,$plainPassword);
            $user->setPassword($encoded);
            $user->setLastname($faker->lastName());
            $user->setFirstname($faker->firstName());
            
            
            $user->setEmail('toto'.$i.'@gmail.com');
            $user->setRoles(['ROLE_ADMIN']);

            $randomDepartement = $departementsList[array_rand($departementsList)];
            $user->setDepartment($randomDepartement);

            $user->setCreatedAt(new \DateTime());
            
            $adminsList[] = $user;

            $manager->persist($user);
        }

        //--- PROPOSITION ---//

        $propositionsList = [];
        for ($i=1; $i <= self::NB_PROPOSITIONS; $i++) {
            
            $proposition = new Proposition();
            $proposition->setTitle($faker->title());
            $proposition->setContent($faker->content());
            $proposition->setDisponibilityDate(new \DateTime());
            $proposition->setCreatedAt(new \DateTime());

            $randomVolunteer = $volunteersList[array_rand($volunteersList)];
            $proposition->setUser($randomVolunteer);

            $randomCategory = $categoriesList[array_rand($categoriesList)];
            $proposition->setCategory($randomCategory);
            
            $propositionsList[] = $proposition;

            $manager->persist($proposition);
        }

        //--- REQUEST ---//

        $requestsList = [];
        for ($i=1; $i <= self::NB_REQUESTS; $i++) {
            
            $request = new Request();
            $request->setTitle($faker->title());
            $request->setContent($faker->content());
            $request->setInterventionDate(new \DateTime());
            $request->setCreatedAt(new \DateTime());

            $randomBeneficiary = $beneficiariesList[array_rand($beneficiariesList)];
            $request->setUser($randomBeneficiary);

            $randomCategory = $categoriesList[array_rand($categoriesList)];
            $request->setCategory($randomCategory);
            
            $requestsList[] = $request;

            $manager->persist($request);
        }


        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
