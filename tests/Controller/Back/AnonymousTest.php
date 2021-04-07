<?php

namespace App\Tests\Controller\Back;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AnonymousTest extends WebTestCase
{
     /**
     * Annotation suivi du nom de la méthode qui fourni les données
     *
     * @dataProvider urlProvider
     */
    public function testRedirectInGet($url): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', $url);

        // On est bien redirigé vers le login
        $this->assertResponseStatusCodeSame(302);
    }

    public function urlProvider()
    {
        yield ['/back/department/browse'];
        yield ['/back/department/read/1'];
        yield ['/back/category/browse'];
        yield ['/back/category/read/1'];
        yield ['/back/request/browse'];
        yield ['/back/request/read/1'];
        yield ['/back/proposition/browse'];
        yield ['/back/proposition/read/1'];
        yield ['/back/admin/browse'];
        yield ['/back/admin/read/13'];
        yield ['/back/beneficiary/browse'];
        yield ['/back/beneficiary/read/1'];
        yield ['/back/volunteer/browse'];
        yield ['/back/volunteer/read/7'];
       
    }

     /**
     * @dataProvider urlProviderForPost
     */
    public function testRedirectInPost($url)
    {
        $client = self::createClient();
        $client->request('POST', $url);

        $this->assertResponseStatusCodeSame(302);
       
    }

    public function urlProviderForPost()
    {
        yield ['/back/category/browse'];
        yield ['/back/category/edit/1'];
        yield ['/back/request/add'];
        yield ['/back/request/edit/1'];
        yield ['/back/proposition/add'];
        yield ['/back/proposition/edit/1'];
        yield ['/back/user/add'];
        yield ['/back/user/edit/1'];
    }

    /**
     * @dataProvider urlProviderForDelete
     */
    public function testRedirectInDelete($url)
    {
        $client = self::createClient();
        $client->request('DELETE', $url);

        $this->assertResponseStatusCodeSame(302);
       
    }

    public function urlProviderForDelete()
    {
        yield ['/back/user/delete/1'];
        yield ['/back/proposition/delete/1'];
        yield ['/back/request/delete/1'];
        yield ['/back/category/delete/1'];
    }
}
