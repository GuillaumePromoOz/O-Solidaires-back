<?php

namespace App\Tests\Controller\Back;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RoleAdminTest extends WebTestCase
{
    /**
     * @dataProvider urlProviderAdminGetSuccessful
     */
    public function testAdminGetSuccessful($url)
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);

        // retrieve the test user
        $testUser = $userRepository->findOneByEmail('toto1@gmail.com');
        // simulate $testUser being logged in
        $client->loginUser($testUser);

        $client->request('GET', $url);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function urlProviderAdminGetSuccessful()
    {
        yield ['/back/department/browse'];
        yield ['/back/department/read/708'];
        yield ['/back/category/browse'];
        yield ['/back/category/read/47'];
        yield ['/back/request/browse'];
        yield ['/back/request/read/58'];
        yield ['/back/proposition/browse'];
        yield ['/back/proposition/read/60'];
        yield ['/back/admin/browse'];
        yield ['/back/admin/read/128'];
        yield ['/back/beneficiary/browse'];
        yield ['/back/beneficiary/read/116'];
        yield ['/back/volunteer/browse'];
        yield ['/back/volunteer/read/122'];
    }
}
