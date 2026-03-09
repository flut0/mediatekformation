<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Tests fonctionnels de l'application
 */
class ApplicationTest extends WebTestCase {

    /**
     * Test que la page d'accueil répond bien
     */
    public function testAccueil(): void {
        $client = static::createClient();
        $client->request('GET', '/');
        $this->assertResponseIsSuccessful();
    }

    /**
     * Test que la page formations répond bien
     */
    public function testFormations(): void {
        $client = static::createClient();
        $client->request('GET', '/formations');
        $this->assertResponseIsSuccessful();
    }

    /**
     * Test que la page playlists répond bien
     */
    public function testPlaylists(): void {
        $client = static::createClient();
        $client->request('GET', '/playlists');
        $this->assertResponseIsSuccessful();
    }

    /**
     * Test que la page admin redirige vers login si non connecté
     */
    public function testAdminRedirectsToLogin(): void {
        $client = static::createClient();
        $client->request('GET', '/admin/formations');
        $this->assertResponseRedirects('/login');
    }
}