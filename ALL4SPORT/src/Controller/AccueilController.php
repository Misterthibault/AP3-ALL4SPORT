<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AccueilController extends AbstractController
{
    #[Route('/accueil', name: 'app_accueil')]
    public function index(): Response
    {
        return $this->render('accueil/index.html.twig', [
            'controller_name' => 'Thibz_AP3',
        ]);
    }

    #[Route('/register', name: 'app_register')]
    public function show(): Response
    {
        return $this->render('registration/register.html.twig', [
        ]);
    }
}
