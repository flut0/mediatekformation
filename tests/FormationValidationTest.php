<?php

namespace App\Tests;

use App\Entity\Formation;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Tests d'intégration sur les règles de validation de Formation
 */
class FormationValidationTest extends KernelTestCase {

    /**
     * Test que la date ne peut pas être postérieure à aujourd'hui
     */
    public function testDateNotInFuture(): void {
        self::bootKernel();
        $validator = static::getContainer()->get('validator');
        
        $formation = new Formation();
        $formation->setTitle('Test formation');
        $formation->setVideoId('testid123');
        $formation->setPublishedAt(new \DateTime('+1 day'));
        
        $errors = $validator->validate($formation);
        $this->assertGreaterThan(0, count($errors));
    }

    /**
     * Test qu'une date valide (aujourd'hui) passe la validation
     */
    public function testDateToday(): void {
        self::bootKernel();
        $validator = static::getContainer()->get('validator');
        
        $formation = new Formation();
        $formation->setTitle('Test formation');
        $formation->setVideoId('testid123');
        $formation->setPublishedAt(new \DateTime('now'));
        
        $errors = $validator->validate($formation);
        $this->assertEquals(0, count($errors));
    }
}