<?php


namespace App\Tests\Controller\Back;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;



class DepartmentControllerTest extends WebTestCase
{
    // public function testbrowse()
    // {
    //     $client = static::createClient();

    //     $client->request('GET', '/back/department/browse');

    //     $this->assertSelectorTextContains('h1', 'Liste des Départements');


    //     // Status 200
    //     $this->assertEquals(200, $client->getResponse()->getStatusCode());
    // }

    // public function testRead()
    // {
    //     $client = static::createClient();

    //     $client->request('GET', '/back/department/read/708');

        

    //     $this->assertEquals(200, $client->getResponse()->getStatusCode());
    // }

    /**
     * L'anonyme n'as pas accès à l'ajout d'une critique
     * En GET
     */
    public function testbrowseFailure()
    {
        
        $client = static::createClient();
        
        $crawler = $client->request('GET', '/back/department/browse');

        // La réponse a un statut 3xx car redirection vers /login
        $this->assertResponseRedirects();
        // $this->assertResponseStatusCodeSame(302);
    }
}