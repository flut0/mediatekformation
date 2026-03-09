<?php

namespace App\Tests;

use App\Entity\Formation;
use PHPUnit\Framework\TestCase;

/**
 * Tests unitaires de l'entité Formation
 */
class FormationTest extends TestCase {

    /**
     * Test de la méthode getPublishedAtString
     * Contrôle que la date est bien retournée au format d/m/Y
     */
    public function testGetPublishedAtString(): void {
        $formation = new Formation();
        $formation->setPublishedAt(new \DateTime('2024-01-15'));
        $this->assertEquals('15/01/2024', $formation->getPublishedAtString());
    }

    /**
     * Test de la méthode getPublishedAtString quand la date est null
     * Contrôle que la méthode retourne une chaine vide
     */
    public function testGetPublishedAtStringAvecNull(): void {
        $formation = new Formation();
        $formation->setPublishedAt(null);
        $this->assertEquals('', $formation->getPublishedAtString());
    }
}