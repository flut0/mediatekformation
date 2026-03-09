<?php

namespace App\Tests;

use App\Entity\Formation;
use App\Entity\Playlist;
use App\Entity\Categorie;
use App\Repository\FormationRepository;
use App\Repository\PlaylistRepository;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Tests d'intégration sur les Repository
 */
class RepositoryTest extends KernelTestCase {

    /**
     * @var FormationRepository
     */
    private $formationRepository;

    /**
     * @var PlaylistRepository
     */
    private $playlistRepository;

    /**
     * @var CategorieRepository
     */
    private $categorieRepository;

    public function setUp(): void {
        self::bootKernel();
        $this->formationRepository = static::getContainer()->get(FormationRepository::class);
        $this->playlistRepository = static::getContainer()->get(PlaylistRepository::class);
        $this->categorieRepository = static::getContainer()->get(CategorieRepository::class);
    }

    /**
     * Test findAllOrderBy sur les formations
     */
    public function testFindAllOrderBy(): void {
        $formations = $this->formationRepository->findAllOrderBy('title', 'ASC');
        $this->assertNotEmpty($formations);
        $this->assertInstanceOf(Formation::class, $formations[0]);
    }

    /**
     * Test findByContainValue sur les formations
     */
    public function testFindByContainValueFormation(): void {
        $formations = $this->formationRepository->findByContainValue('title', 'a');
        $this->assertIsArray($formations);
    }

    /**
     * Test findAllOrderByName sur les playlists
     */
    public function testFindAllOrderByName(): void {
        $playlists = $this->playlistRepository->findAllOrderByName('ASC');
        $this->assertNotEmpty($playlists);
        $this->assertInstanceOf(Playlist::class, $playlists[0]);
    }

    /**
     * Test findAllOrderByNbFormations sur les playlists
     */
    public function testFindAllOrderByNbFormations(): void {
        $playlists = $this->playlistRepository->findAllOrderByNbFormations('DESC');
        $this->assertNotEmpty($playlists);
        $this->assertInstanceOf(Playlist::class, $playlists[0]);
    }

    /**
     * Test findByContainValue sur les playlists
     */
    public function testFindByContainValuePlaylist(): void {
        $playlists = $this->playlistRepository->findByContainValue('name', 'a');
        $this->assertIsArray($playlists);
    }

    /**
     * Test findAll sur les catégories
     */
    public function testFindAllCategories(): void {
        $categories = $this->categorieRepository->findAll();
        $this->assertNotEmpty($categories);
        $this->assertInstanceOf(Categorie::class, $categories[0]);
    }

    /**
     * Test findAllForOnePlaylist sur les catégories
     */
    public function testFindAllForOnePlaylist(): void {
        $playlists = $this->playlistRepository->findAllOrderByName('ASC');
        $id = $playlists[0]->getId();
        $categories = $this->categorieRepository->findAllForOnePlaylist($id);
        $this->assertIsArray($categories);
    }

    /**
     * Test findAllForOnePlaylist sur les formations
     */
    public function testFindAllForOnePlaylistFormations(): void {
        $playlists = $this->playlistRepository->findAllOrderByName('ASC');
        $id = $playlists[0]->getId();
        $formations = $this->formationRepository->findAllForOnePlaylist($id);
        $this->assertIsArray($formations);
    }
}