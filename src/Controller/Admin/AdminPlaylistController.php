<?php

namespace App\Controller\Admin;

use App\Entity\Playlist;
use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use App\Repository\PlaylistRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controleur admin des playlists
 */
#[Route('/admin')]
class AdminPlaylistController extends AbstractController {

    /**
     * @var PlaylistRepository
     */
    private $playlistRepository;

    /**
     * @var CategorieRepository
     */
    private $categorieRepository;

    /**
     * @var FormationRepository
     */
    private $formationRepository;

    public function __construct(PlaylistRepository $playlistRepository,
            CategorieRepository $categorieRepository,
            FormationRepository $formationRepository) {
        $this->playlistRepository = $playlistRepository;
        $this->categorieRepository = $categorieRepository;
        $this->formationRepository = $formationRepository;
    }

    #[Route('/playlists', name: 'admin.playlists')]
    public function index(): Response {
        $playlists = $this->playlistRepository->findAllOrderByName('ASC');
        $categories = $this->categorieRepository->findAll();
        return $this->render('admin/playlists.html.twig', [
            'playlists' => $playlists,
            'categories' => $categories
        ]);
    }

    #[Route('/playlists/tri/{champ}/{ordre}', name: 'admin.playlists.sort')]
    public function sort($champ, $ordre): Response {
        switch($champ){
            case "name":
                $playlists = $this->playlistRepository->findAllOrderByName($ordre);
                break;
            case "nbFormations":
                $playlists = $this->playlistRepository->findAllOrderByNbFormations($ordre);
                break;
            default:
                $playlists = $this->playlistRepository->findAllOrderByName('ASC');
                break;
        }
        $categories = $this->categorieRepository->findAll();
        return $this->render('admin/playlists.html.twig', [
            'playlists' => $playlists,
            'categories' => $categories
        ]);
    }

    #[Route('/playlists/recherche/{champ}/{table}', name: 'admin.playlists.findallcontain')]
    public function findAllContain($champ, Request $request, $table=""): Response {
        $valeur = $request->get("recherche");
        $playlists = $this->playlistRepository->findByContainValue($champ, $valeur, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render('admin/playlists.html.twig', [
            'playlists' => $playlists,
            'categories' => $categories,
            'valeur' => $valeur,
            'table' => $table
        ]);
    }

    #[Route('/playlists/supprimer/{id}', name: 'admin.playlists.supprimer')]
    public function supprimer($id): Response {
        $playlist = $this->playlistRepository->find($id);
        if($playlist->getFormations()->count() > 0){
            return $this->redirectToRoute('admin.playlists');
        }
        $this->playlistRepository->remove($playlist);
        return $this->redirectToRoute('admin.playlists');
    }

    #[Route('/playlists/ajout', name: 'admin.playlists.ajout')]
    public function ajout(Request $request): Response {
        $playlist = new Playlist();
        if ($request->isMethod('POST')) {
            $playlist->setName($request->get('name'));
            $playlist->setDescription($request->get('description'));
            $this->playlistRepository->add($playlist);
            return $this->redirectToRoute('admin.playlists');
        }
        return $this->render('admin/playlist_form.html.twig', [
            'playlist' => $playlist
        ]);
    }

    #[Route('/playlists/modifier/{id}', name: 'admin.playlists.modifier')]
    public function modifier($id, Request $request): Response {
        $playlist = $this->playlistRepository->find($id);
        $formations = $this->formationRepository->findAllForOnePlaylist($id);
        if ($request->isMethod('POST')) {
            $playlist->setName($request->get('name'));
            $playlist->setDescription($request->get('description'));
            $this->playlistRepository->add($playlist);
            return $this->redirectToRoute('admin.playlists');
        }
        return $this->render('admin/playlist_form.html.twig', [
            'playlist' => $playlist,
            'formations' => $formations
        ]);
    }
}