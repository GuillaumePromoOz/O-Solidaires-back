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

    //We inject the service password encoder
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {

        $this->passwordEncoder = $passwordEncoder;
    }

    //we declare constants that contain the number of fixtures needed
    const NB_USERS = 10;
    const NB_PROPOSITIONS = 2 * self::NB_USERS;
    const NB_REQUESTS = 2 * self::NB_USERS;


    public function load(ObjectManager $manager)
    {
        // Faker is a PHP library that generates fake data
        $faker = Factory::create('fr_FR');
        // Seed helps us generate the same data with each fixtures load 
        // (if for example you want to inject a new entity and user fixtures for that it won't generate a whole new batch of fake data)
        $faker->seed('osolidaires');
        // we to the Faker service our custom datas (@see OsolidaireProvider.php)
        $faker->addProvider(new OsolidaireProvider());


        // ---- DEPARTMENT ---- //

        // Departments list
        $departments = $faker->getDepartments();

        // Used to create a department list, not vital because we could also use the above getter (getDepartments)
        $departmentsList = [];

        // the loop iterates as many times as there are entries in the departments array
        foreach ($departments as $content) {
            // we create an instance of Department
            $department = new Department();
            // we set the department's name
            $department->setName($content);
            // we set the creation date of the department
            $department->setCreatedAt(new \DateTime());
            // We store a department inside the array 
            $departementsList[] = $department;
            $manager->persist($department);
        }

        //--- CATEGORY ---//
        $categories = $faker->getCategories();
        $categoriesList = [];
        foreach ($categories as $content) {
            $categorie = new Category();
            $categorie->setName($content);
            $categorie->setCreatedAt(new \DateTime());
            $categoriesList[] = $categorie;
            $manager->persist($categorie);
        }

        //--- BENEFICIARY ---//

        // we declare a variable that will store a list of beneficiaries
        $beneficiariesList = [];

        // the loop iterates as many times as the number that is stored in the constant NB_USERS (in this case it's 10)
        for ($i = 1; $i <= self::NB_USERS; $i++) {

            // we create an instance of User
            $user = new User();
            // we store the password into a variable
            $plainPassword = 'Az123456';
            // we encode the password using the encodePassword service
            $encoded = $this->passwordEncoder->encodePassword($user, $plainPassword);
            // we assign this password to the current user
            $user->setPassword($encoded);
            // we set the lastName
            $user->setLastname($faker->lastName());
            // we set the firstname
            $user->setFirstname($faker->firstName());

            // we create an email using the user's name (user->getLastname)
            // using preg_replace we cut out the spaces
            // using strtolower we get rid of any CAPS
            // we concatenate @gmail.com
            $user->setEmail(strtolower(preg_replace('/\s+/', '', $user->getLastname())) . '@gmail.com');
            // we set the user's role to beneficiary
            $user->setRoles(['ROLE_BENEFICIARY']);
            // we fetch a random Department in the departementsList
            $randomDepartement = $departementsList[array_rand($departementsList)];
            // we assign this department to the current user
            $user->setDepartment($randomDepartement);
            // we set the creation date of the beneficiary
            $user->setCreatedAt(new \DateTime());
            // We store a beneficiary inside the array 
            $beneficiariesList[] = $user;

            $manager->persist($user);
        }

        //--- VOLUNTEER ---//

        $volunteersList = [];
        for ($i = 1; $i <= self::NB_USERS; $i++) {

            $user = new User();
            $plainPassword = 'Az123456';
            $encoded = $this->passwordEncoder->encodePassword($user, $plainPassword);
            $user->setPassword($encoded);
            $user->setLastname($faker->lastName());
            $user->setFirstname($faker->firstName());


            $user->setEmail(strtolower(preg_replace('/\s+/', '', $user->getLastname())) . '@gmail.com');
            $user->setRoles(['ROLE_VOLUNTEER']);

            $randomDepartement = $departementsList[array_rand($departementsList)];
            $user->setDepartment($randomDepartement);

            $user->setCreatedAt(new \DateTime());
            $volunteersList[] = $user;

            $manager->persist($user);
        }

        //--- ADMIN ---//

        $adminsList = [];
        for ($i = 1; $i <= 2; $i++) {

            $user = new User();
            $plainPassword = 'Az123456';
            $encoded = $this->passwordEncoder->encodePassword($user, $plainPassword);
            $user->setPassword($encoded);
            $user->setLastname($faker->lastName());
            $user->setFirstname($faker->firstName());


            $user->setEmail('toto' . $i . '@gmail.com');
            $user->setRoles(['ROLE_ADMIN']);

            $randomDepartement = $departementsList[array_rand($departementsList)];
            $user->setDepartment($randomDepartement);

            $user->setCreatedAt(new \DateTime());

            $adminsList[] = $user;

            $manager->persist($user);
        }

        //--- PROPOSITION ---//

        // we declare a variable that will store a list of propositions
        $propositionsList = [];
        // the loop iterates as many times as the number that is stored in the constant NB_PROPOSITIONS (in this case it's 2 * self::NB_USERS)
        for ($i = 1; $i <= self::NB_PROPOSITIONS; $i++) {
            // we create an instance of Proposition
            $proposition = new Proposition();
            // we set a title using the Faker service
            $proposition->setTitle($faker->title());
            // we set a content using the Faker service
            $proposition->setContent($faker->content());
            // we set the disponibility date of the proposition
            $proposition->setDisponibilityDate(new \DateTime());
            // we set the creation date of the proposition
            $proposition->setCreatedAt(new \DateTime());
            // we fetch a random volunteer in the volunteersList
            $randomVolunteer = $volunteersList[array_rand($volunteersList)];
            // we assing this volunteer to the current proposition
            $proposition->setUser($randomVolunteer);
            // we fetch a random category in the categoriesList
            $randomCategory = $categoriesList[array_rand($categoriesList)];
            //  we assing this category to the current proposition
            $proposition->setCategory($randomCategory);

            $propositionsList[] = $proposition;

            $manager->persist($proposition);
        }

        //--- REQUEST ---//

        $requestsList = [];
        for ($i = 1; $i <= self::NB_REQUESTS; $i++) {

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

        $manager->flush();
    }
}
