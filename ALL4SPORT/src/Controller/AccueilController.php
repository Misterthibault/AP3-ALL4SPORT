<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class AccueilController extends AbstractController
{
    #[Route('/accueil', name: 'app_accueil')]
    public function index(ProduitRepository $produitRepository): Response
    {
        $produit = $produitRepository->findAll();
        return $this->render('accueil/index.html.twig', [
            'produits' => $produit,
        ]);
    }

    #[Route('/produit_show/{id}', name: 'produit_show')]         // route portant un nom, permet de l'appeler depuis n'importe où dans l'application plus facilement
    public function show(produit $produit): Response
    {
        // $produit = $produitRepository->find($id);
        return $this->render('accueil/produit_show.html.twig', [
            'produit' => $produit,
        ]);
    }

    #[Route('/register', name: 'app_register')]
    public function register(): Response
    {
        return $this->render('registration/register.html.twig', []);
    }

    // #[Route('/login', name: 'app_logout')]
    // public function logout(): Response
    // {
    //     return $this->render('/login.html.twig', []);
    // }

    #[Route('/panier', name: 'app_panier')]
    public function panier(): Response
    {
        return $this->render('panier/index.html.twig', [
            'controller_name' => 'PanierController',
        ]);
    }

    #[Route('/accueil', name: 'app_produit')]         // route portant un nom, permet de l'appeler depuis n'importe où dans l'application plus facilement
    public function listProduit(ProduitRepository $produitRepository): Response
    {
        $produits = $produitRepository->findAll();
        return $this->render('accueil/index.html.twig', [
            'produits' => $produits,
        ]);
    }

    #[Route('/profil', name: 'app_profil')]
    #[IsGranted('ROLE_USER')]
    public function profil(): Response
    {
        return $this->render('profil/index.html.twig', []);
    }
}
