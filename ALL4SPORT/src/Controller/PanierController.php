<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Commande;
use App\Entity\HistoriqueCommande;
use App\Entity\LigneCommande;
use App\Entity\Produit;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
final class PanierController extends AbstractController
{
    private const CART_KEY = 'cart';
    private const MAX_QUANTITY = 99;

    #[Route('/panier', name: 'app_panier', methods: ['GET'])]
    public function panier(Request $request, ProduitRepository $produitRepository): Response
    {
        $cart = $this->getCart($request);
        $products = $cart === [] ? [] : $produitRepository->findBy(['id' => array_keys($cart)]);
        $productsById = [];
        foreach ($products as $product) {
            $productsById[$product->getId()] = $product;
        }

        $lines = [];
        $total = 0.0;
        $itemCount = 0;
        foreach ($cart as $productId => $quantity) {
            $product = $productsById[$productId] ?? null;
            if ($product === null) {
                unset($cart[$productId]);
                continue;
            }

            $lineTotal = round(($product->getPrixVente() ?? 0) * $quantity, 2);
            $lines[] = [
                'produit' => $product,
                'quantite' => $quantity,
                'total' => $lineTotal,
            ];
            $total += $lineTotal;
            $itemCount += $quantity;
        }

        $request->getSession()->set(self::CART_KEY, $cart);

        return $this->render('panier/index.html.twig', [
            'lignes' => $lines,
            'total' => round($total, 2),
            'nombreArticles' => $itemCount,
            'adresseLivraison' => $this->getUser() instanceof Client ? $this->getUser()->getAdresse() : null,
        ]);
    }

    #[Route('/panier/ajouter/{id}', name: 'app_panier_ajouter', methods: ['POST'])]
    public function ajouter(Produit $produit, Request $request): Response
    {
        if (!$this->isCsrfTokenValid('panier_ajouter_'.$produit->getId(), $request->getPayload()->getString('_token'))) {
            throw $this->createAccessDeniedException('Jeton CSRF invalide.');
        }

        $cart = $this->getCart($request);
        $productId = $produit->getId();
        if (($cart[$productId] ?? 0) >= self::MAX_QUANTITY) {
            $this->addFlash('error', sprintf('La quantité maximale par produit est de %d.', self::MAX_QUANTITY));

            return $this->redirectToRoute('app_panier');
        }

        $cart[$productId] = min(self::MAX_QUANTITY, ($cart[$productId] ?? 0) + 1);
        $request->getSession()->set(self::CART_KEY, $cart);
        $this->addFlash('success', sprintf('%s a été ajouté à votre panier.', $produit->getNom()));

        return $this->redirectToRoute('app_panier');
    }

    #[Route('/panier/modifier/{id}', name: 'app_panier_modifier', methods: ['POST'])]
    public function modifier(Produit $produit, Request $request): Response
    {
        if (!$this->isCsrfTokenValid('panier_modifier_'.$produit->getId(), $request->getPayload()->getString('_token'))) {
            throw $this->createAccessDeniedException('Jeton CSRF invalide.');
        }

        $quantity = filter_var($request->getPayload()->get('quantite'), FILTER_VALIDATE_INT);
        if ($quantity === false || $quantity < 1 || $quantity > self::MAX_QUANTITY) {
            $this->addFlash('error', sprintf('La quantité doit être comprise entre 1 et %d.', self::MAX_QUANTITY));

            return $this->redirectToRoute('app_panier');
        }

        $cart = $this->getCart($request);
        if (!array_key_exists($produit->getId(), $cart)) {
            $this->addFlash('error', 'Ce produit ne se trouve pas dans votre panier.');

            return $this->redirectToRoute('app_panier');
        }

        $cart[$produit->getId()] = $quantity;
        $request->getSession()->set(self::CART_KEY, $cart);

        return $this->redirectToRoute('app_panier');
    }

    #[Route('/panier/supprimer/{id}', name: 'app_panier_supprimer', methods: ['POST'])]
    public function supprimer(Produit $produit, Request $request): Response
    {
        if (!$this->isCsrfTokenValid('panier_supprimer_'.$produit->getId(), $request->getPayload()->getString('_token'))) {
            throw $this->createAccessDeniedException('Jeton CSRF invalide.');
        }

        $cart = $this->getCart($request);
        unset($cart[$produit->getId()]);
        $request->getSession()->set(self::CART_KEY, $cart);

        return $this->redirectToRoute('app_panier');
    }

    #[Route('/panier/commander', name: 'app_panier_commander', methods: ['POST'])]
    public function commander(
        Request $request,
        ProduitRepository $produitRepository,
        EntityManagerInterface $entityManager,
    ): Response {
        if (!$this->isCsrfTokenValid('panier_commander', $request->getPayload()->getString('_token'))) {
            throw $this->createAccessDeniedException('Jeton CSRF invalide.');
        }

        $cart = $this->getCart($request);
        if ($cart === []) {
            $this->addFlash('error', 'Votre panier est vide.');

            return $this->redirectToRoute('app_panier');
        }

        $client = $this->getUser();
        if (!$client instanceof Client || trim((string) $client->getAdresse()) === '') {
            $this->addFlash('error', 'Veuillez renseigner une adresse dans votre profil avant de commander.');

            return $this->redirectToRoute('app_profil');
        }

        $products = $produitRepository->findBy(['id' => array_keys($cart)]);
        $productsById = [];
        foreach ($products as $product) {
            $productsById[$product->getId()] = $product;
        }

        if (count($productsById) !== count($cart)) {
            $this->addFlash('error', 'Un ou plusieurs produits de votre panier ne sont plus disponibles. Vérifiez votre panier.');

            return $this->redirectToRoute('app_panier');
        }

        $dateCommande = new \DateTime();
        $commande = (new Commande())
            ->setClient($client)
            ->setDateCommande($dateCommande)
            ->setLieuExpedition('ALL4SPORT')
            ->setAddresseLivraison($client->getAdresse());

        $total = 0.0;
        foreach ($cart as $productId => $quantity) {
            $product = $productsById[$productId];
            $price = $product->getPrixVente() ?? 0;
            $total += round($price * $quantity, 2);

            $commande->addProduitId($product);
            $commande->addLigne((new LigneCommande())
                ->setProduit($product)
                ->setNomProduit($product->getNom() ?? '')
                ->setPrixUnitaire($price)
                ->setQuantite($quantity));
        }

        $commande->setCoutTotal(round($total, 2));
        $historique = (new HistoriqueCommande())
            ->setEtat('Commandée')
            ->setDateCommande($dateCommande)
            ->setCommandeTelClient($client->getTelephone() ?? '');

        $entityManager->wrapInTransaction(function (EntityManagerInterface $entityManager) use ($commande, $historique, $cart): void {
            $entityManager->persist($commande);
            $entityManager->persist($historique);
            $entityManager->flush();

            foreach ($cart as $productId => $quantity) {
                $entityManager->getConnection()->executeStatement(
                    'UPDATE commande_produit SET quantite = :quantite WHERE commande_id = :commandeId AND produit_id = :produitId',
                    [
                        'quantite' => $quantity,
                        'commandeId' => $commande->getId(),
                        'produitId' => $productId,
                    ],
                );
            }
        });

        $request->getSession()->remove(self::CART_KEY);
        $this->addFlash('success', sprintf('Votre commande n°%d a bien été enregistrée.', $commande->getId()));

        return $this->redirectToRoute('app_panier');
    }

    /**
     * @return array<int, int>
     */
    private function getCart(Request $request): array
    {
        $cart = $request->getSession()->get(self::CART_KEY, []);
        if (!is_array($cart)) {
            return [];
        }

        $validCart = [];
        foreach ($cart as $productId => $quantity) {
            $productId = filter_var($productId, FILTER_VALIDATE_INT);
            $quantity = filter_var($quantity, FILTER_VALIDATE_INT);
            if ($productId !== false && $productId > 0 && $quantity !== false && $quantity > 0) {
                $validCart[$productId] = min(self::MAX_QUANTITY, $quantity);
            }
        }

        return $validCart;
    }
}
