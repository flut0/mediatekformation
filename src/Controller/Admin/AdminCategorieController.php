<?php

namespace App\Controller\Admin;

use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controleur admin des catégories
 */
#[Route('/admin')]
class AdminCategorieController extends AbstractController {

    /**
     * @var CategorieRepository
     */
    private $categorieRepository;

    public function __construct(CategorieRepository $categorieRepository) {
        $this->categorieRepository = $categorieRepository;
    }

    /**
     * Affiche la liste des catégories et traite l'ajout d'une nouvelle catégorie
     * @param Request $request requête HTTP
     * @return Response
     */
    #[Route('/categories', name: 'admin.categories')]
    public function index(Request $request): Response {
        $categories = $this->categorieRepository->findAll();
        $error = null;
        if ($request->isMethod('POST')) {
            $name = $request->get('name');
            if (empty($name)) {
                $error = "Le nom de la catégorie est obligatoire.";
            } else {
                $existe = $this->categorieRepository->findOneBy(['name' => $name]);
                if ($existe) {
                    $error = "Cette catégorie existe déjà.";
                } else {
                    $categorie = new Categorie();
                    $categorie->setName($name);
                    $this->categorieRepository->add($categorie);
                    return $this->redirectToRoute('admin.categories');
                }
            }
        }
        return $this->render('admin/categories.html.twig', [
            'categories' => $categories,
            'error' => $error
        ]);
    }

    /**
     * Supprime une catégorie si elle n'est rattachée à aucune formation
     * @param int $id identifiant de la catégorie
     * @return Response
     */
    #[Route('/categories/supprimer/{id}', name: 'admin.categories.supprimer')]
    public function supprimer($id): Response {
        $categorie = $this->categorieRepository->find($id);
        if ($categorie->getFormations()->count() == 0) {
            $this->categorieRepository->remove($categorie);
        }
        return $this->redirectToRoute('admin.categories');
    }
}