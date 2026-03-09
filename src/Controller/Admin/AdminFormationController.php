<?php

namespace App\Controller\Admin;

use App\Entity\Formation;
use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use App\Repository\PlaylistRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controleur admin des formations
 */
#[Route('/admin')]
class AdminFormationController extends AbstractController {

    /**
     * @var FormationRepository
     */
    private $formationRepository;

    /**
     * @var CategorieRepository
     */
    private $categorieRepository;

    /**
     * @var PlaylistRepository
     */
    private $playlistRepository;

    public function __construct(FormationRepository $formationRepository,
            CategorieRepository $categorieRepository,
            PlaylistRepository $playlistRepository) {
        $this->formationRepository = $formationRepository;
        $this->categorieRepository = $categorieRepository;
        $this->playlistRepository = $playlistRepository;
    }

    /**
     * Affiche la liste des formations dans l'admin
     * @return Response
     */
    #[Route('/formations', name: 'admin.formations')]
    public function index(): Response {
        $formations = $this->formationRepository->findAllOrderBy('publishedAt', 'DESC');
        $categories = $this->categorieRepository->findAll();
        return $this->render('admin/formations.html.twig', [
            'formations' => $formations,
            'categories' => $categories
        ]);
    }

    /**
     * Trie les formations sur un champ
     * @param string $champ champ sur lequel trier
     * @param string $ordre ordre de tri (ASC ou DESC)
     * @param string $table table liée si le champ est dans une autre table
     * @return Response
     */
    #[Route('/formations/tri/{champ}/{ordre}/{table}', name: 'admin.formations.sort')]
    public function sort($champ, $ordre, $table=""): Response {
        $formations = $this->formationRepository->findAllOrderBy($champ, $ordre, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render('admin/formations.html.twig', [
            'formations' => $formations,
            'categories' => $categories
        ]);
    }

    /**
     * Recherche les formations dont un champ contient une valeur
     * @param string $champ champ sur lequel rechercher
     * @param Request $request requête HTTP
     * @param string $table table liée si le champ est dans une autre table
     * @return Response
     */
    #[Route('/formations/recherche/{champ}/{table}', name: 'admin.formations.findallcontain')]
    public function findAllContain($champ, Request $request, $table=""): Response {
        $valeur = $request->get("recherche");
        $formations = $this->formationRepository->findByContainValue($champ, $valeur, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render('admin/formations.html.twig', [
            'formations' => $formations,
            'categories' => $categories,
            'valeur' => $valeur,
            'table' => $table
        ]);
    }

    /**
     * Supprime une formation
     * @param int $id identifiant de la formation
     * @return Response
     */
    #[Route('/formations/supprimer/{id}', name: 'admin.formations.supprimer')]
    public function supprimer($id): Response {
        $formation = $this->formationRepository->find($id);
        $this->formationRepository->remove($formation);
        return $this->redirectToRoute('admin.formations');
    }

    /**
     * Affiche le formulaire d'ajout d'une formation et traite la soumission
     * @param Request $request requête HTTP
     * @return Response
     */
    #[Route('/formations/ajout', name: 'admin.formations.ajout')]
    public function ajout(Request $request): Response {
        $formation = new Formation();
        $playlists = $this->playlistRepository->findAll();
        $categories = $this->categorieRepository->findAll();
        if ($request->isMethod('POST')) {
            $formation->setTitle($request->get('title'));
            $formation->setDescription($request->get('description'));
            $formation->setVideoId($request->get('videoId'));
            $formation->setPublishedAt(new \DateTime($request->get('publishedAt')));
            $playlist = $this->playlistRepository->find($request->get('playlist'));
            $formation->setPlaylist($playlist);
            foreach ($request->get('categories', []) as $categorieId) {
                $categorie = $this->categorieRepository->find($categorieId);
                $formation->addCategory($categorie);
            }
            $this->formationRepository->add($formation);
            return $this->redirectToRoute('admin.formations');
        }
        return $this->render('admin/formation_form.html.twig', [
            'formation' => $formation,
            'playlists' => $playlists,
            'categories' => $categories
        ]);
    }

    /**
     * Affiche le formulaire de modification d'une formation et traite la soumission
     * @param int $id identifiant de la formation
     * @param Request $request requête HTTP
     * @return Response
     */
    #[Route('/formations/modifier/{id}', name: 'admin.formations.modifier')]
    public function modifier($id, Request $request): Response {
        $formation = $this->formationRepository->find($id);
        $playlists = $this->playlistRepository->findAll();
        $categories = $this->categorieRepository->findAll();
        if ($request->isMethod('POST')) {
            $formation->setTitle($request->get('title'));
            $formation->setDescription($request->get('description'));
            $formation->setVideoId($request->get('videoId'));
            $formation->setPublishedAt(new \DateTime($request->get('publishedAt')));
            $playlist = $this->playlistRepository->find($request->get('playlist'));
            $formation->setPlaylist($playlist);
            foreach ($formation->getCategories() as $cat) {
                $formation->removeCategory($cat);
            }
            foreach ($request->get('categories', []) as $categorieId) {
                $categorie = $this->categorieRepository->find($categorieId);
                $formation->addCategory($categorie);
            }
            $this->formationRepository->add($formation);
            return $this->redirectToRoute('admin.formations');
        }
        return $this->render('admin/formation_form.html.twig', [
            'formation' => $formation,
            'playlists' => $playlists,
            'categories' => $categories
        ]);
    }
}