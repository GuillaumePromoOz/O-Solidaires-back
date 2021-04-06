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



    public function load(ObjectManager $manager)
    {
        // Faker is a PHP library that generates fake data
        // @see https://fakerphp.github.io/formatters/
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
            $departmentsList[] = $department;
            $manager->persist($department);
        }

        //--- CATEGORY ---//
        $categories = $faker->getCategories();
        foreach ($categories as $content) {
            $categorie = new Category();
            $categorie->setName($content);
            $categorie->setCreatedAt(new \DateTime());
            $manager->persist($categorie);
        }

        //--- BENEFICIARY ---//

        $beneficiaries = $faker->getBeneficiairies();
        // we declare a variable that will store a list of beneficiaries
        $beneficiariesList = [];

        // the first loop browses through the array $beneficiaries with a key and a content
        foreach ($beneficiaries as $key => $content) {

            // we create an instance of User
            $user = new User();
            // we store the password into a variable
            $plainPassword = 'Az123456';
            // we encode the password using the encodePassword service
            $encoded = $this->passwordEncoder->encodePassword($user, $plainPassword);
            // we assign this password to the current user
            $user->setPassword($encoded);
            // we set the phone number
            $user->setPhoneNumber($faker->phoneNumber());
            // the second loop browses through the array $content as it is a multidimensional array to fetch the key and value in that second array
            foreach ($content as $key => $value) {
                if ($key === 'lastname') {
                    // we set the lastName
                    $user->setLastname($value);
                }
                if ($key === 'firstname') {
                    // we set the firstname
                    $user->setFirstname($value);
                }
                if ($key === 'bio') {
                    // we set the user's biography
                    $user->setBio($value);
                }
            }

            // we create an email using the user's name (user->getLastname)
            // using preg_replace we cut out the spaces
            // using strtolower we get rid of any CAPS
            // we concatenate @gmail.com
            $user->setEmail(strtolower(preg_replace('/\s+/', '', $user->getLastname())) . '@gmail.com');
            // we set the user's role to beneficiary
            $user->setRoles(['ROLE_BENEFICIARY']);
            // we use this condition to dispatch one department for three users, 
            // so for instance if it's key 1 or 2 or 3 the user will be attributed department [30]
            if ($key === 1 || $key === 2 || $key === 3) {
                // we assign this department to the current user
                $user->setDepartment($departmentsList[30]);
            } else {
                $user->setDepartment($departmentsList[76]);
            }

            // we set the creation date of the beneficiary
            $user->setCreatedAt(new \DateTime());
            // We store a beneficiary inside the array 
            $beneficiariesList[] = $user;

            $manager->persist($user);
        }

        //--- VOLUNTEER ---//

        $volunteers = $faker->getVolunteers();
        $volunteersList = [];
        foreach ($volunteers as $key => $content) {


            $user = new User();
            $plainPassword = 'Az123456';
            $encoded = $this->passwordEncoder->encodePassword($user, $plainPassword);
            $user->setPassword($encoded);
            $user->setPhoneNumber($faker->phoneNumber());

            foreach ($content as $key => $value) {
                if ($key === 'lastname') {
                    $user->setLastname($value);
                }
                if ($key === 'firstname') {
                    $user->setFirstname($value);
                }
                if ($key === 'bio') {
                    $user->setBio($value);
                }
            }

            $user->setEmail(strtolower(preg_replace('/\s+/', '', $user->getLastname())) . '@gmail.com');
            $user->setRoles(['ROLE_VOLUNTEER']);

            if ($key === 1 || $key === 2 || $key === 3) {
                $user->setDepartment($departmentsList[30]);
            } else {
                $user->setDepartment($departmentsList[76]);
            }

            $user->setCreatedAt(new \DateTime());
            $volunteersList[] = $user;

            $manager->persist($user);
        }

        //--- ADMIN ---//

        for ($i = 1; $i <= 2; $i++) {

            $user = new User();
            $plainPassword = 'Az123456';
            $encoded = $this->passwordEncoder->encodePassword($user, $plainPassword);
            $user->setPassword($encoded);
            $user->setLastname($faker->lastName());
            $user->setFirstname($faker->firstName());


            $user->setEmail('toto' . $i . '@gmail.com');
            $user->setRoles(['ROLE_ADMIN']);

            // we set the phone number
            $user->setPhoneNumber($faker->phoneNumber());
            // we set the user's biography
            $user->setBio($faker->paragraph());


            if ($i === 1) {
                $user->setDepartment($departmentsList[30]);
            } else {
                $user->setDepartment($departmentsList[76]);
            }

            $user->setCreatedAt(new \DateTime());

            $manager->persist($user);
        }

        //--- PROPOSITION ---//

        $propositions = $faker->getPropositions();

        foreach ($propositions as $key => $content) {
            // we create an instance of Proposition
            $proposition = new Proposition();

            foreach ($content as $key => $value) {
                if ($key === 'title') {
                    // we set a title using the Faker service
                    $proposition->setTitle($value);
                }
                if ($key === 'content') {
                    // we set a content using the Faker service
                    $proposition->setContent($value);
                }
                if ($key === 'category') {
                    //  we assing this category to the current proposition
                    $proposition->setCategory($value);
                }
            }



            // we set the disponibility date of the proposition
            $proposition->setDisponibilityDate(new \DateTime());
            // we set the creation date of the proposition
            $proposition->setCreatedAt(new \DateTime());
            // we fetch a volunteer in the volunteersList
            $volunteer = $volunteersList[$key - 1];
            // we assing this volunteer to the current proposition
            $proposition->setUser($volunteer);

            $manager->persist($proposition);
        }

        //--- REQUEST ---//

        $requests = $faker->getRequests();
        // the loop iterates as many times as the number that is stored in the constant NB_PROPOSITIONS (in this case it's 2 * self::NB_USERS)
        foreach ($requests as $key => $content) {
            // we create an instance of Proposition
            $request = new Request();

            foreach ($content as $key => $value) {
                if ($key === 'title') {
                    // we set a title using the Faker service
                    $request->setTitle($value);
                }
                if ($key === 'content') {
                    // we set a content using the Faker service
                    $request->setContent($value);
                }
                if ($key === 'category') {
                    //  we assing this category to the current proposition
                    $request->setCategory($value);
                }
            }



            // we set the disponibility date of the proposition
            $request->setInterventionDate(new \DateTime());
            // we set the creation date of the proposition
            $request->setCreatedAt(new \DateTime());
            // we fetch a volunteer in the volunteersList
            $beneficiary = $beneficiariesList[$key - 1];
            // we assing this volunteer to the current proposition
            $request->setUser($beneficiary);

            $manager->persist($request);
        }

        $manager->flush();
    }
}
