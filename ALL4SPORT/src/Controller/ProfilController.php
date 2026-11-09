<?php

namespace App\Controller;

use App\Entity\Client;
use App\Repository\CommandeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
final class ProfilController extends AbstractController
{
    #[Route('/profil', name: 'app_profil')]
    public function index(CommandeRepository $commandeRepository): Response
    {
        $client = $this->getUser();
        if (!$client instanceof Client) {
            throw $this->createAccessDeniedException();
        }

        return $this->render('profil/index.html.twig', [
            'commandes' => $commandeRepository->findBy(
                ['client' => $client],
                ['date_commande' => 'DESC'],
            ),
        ]);
    }
}
