<?php

namespace App\Tests\Controller\Back;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Sur toutes ces URLs
 * Le rôle ANONYMOUS se fait rediriger vers le login
 * => donc réponse = redirection
 * 
 * On ne teste que les ACLs ici
 */
class RoleAnonymousTest extends WebTestCase
{
    /**
     * @dataProvider backendUrlsProvider
     */
    public function testBrowse($method, $url): void
    {
        $client = static::createClient();
        $crawler = $client->request($method, $url);

        // Le user se fait rediriger
        $this->assertResponseRedirects();
    }

    /**
     * Les méthodes/URLs à tester
     */
    public function backendUrlsProvider()
    {
        // Movie
        yield ['GET', '/back/movie/browse'];
        yield ['GET', '/back/movie/read/1'];
        yield ['GET', '/back/movie/edit/1'];
        yield ['POST', '/back/movie/edit/1'];
        yield ['GET', '/back/movie/add'];
        yield ['POST', '/back/movie/add'];
        yield ['GET', '/back/movie/delete/1'];
        // User
        yield ['GET', '/back/user/browse'];
        yield ['GET', '/back/user/read/1'];
        yield ['GET', '/back/user/edit/1'];
        yield ['POST', '/back/user/edit/1'];
        yield ['GET', '/back/user/add'];
        yield ['POST', '/back/user/add'];
        yield ['GET', '/back/user/delete/1'];
    }
}