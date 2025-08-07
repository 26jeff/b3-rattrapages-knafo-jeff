<?php

namespace App\Controller;

use App\Entity\Basket;
use App\Entity\BasketEntry;
use App\Entity\Article;
use App\Repository\BasketRepository;
use App\Repository\BasketEntryRepository;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class BasketController extends AbstractController
{
    #[Route('/basket', name: 'app_basket')]
    public function index(): Response
    {
        return $this->render('basket/index.html.twig', [
            'controller_name' => 'BasketController',
        ]);
    }

    #[Route('/api/basket/{basketId}/add-article', name: 'api_basket_add_article', methods: ['POST'])]
    public function addToBasket(
        int $basketId,
        Request $request,
        BasketRepository $basketRepository,
        ArticleRepository $articleRepository,
        BasketEntryRepository $basketEntryRepository,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['articleId']) || !isset($data['quantity'])) {
            return $this->json(['error' => 'articleId and quantity are required'], 400);
        }

        $basket = $basketRepository->find($basketId);
        if (!$basket) {
            return $this->json(['error' => 'Basket not found'], 404);
        }

        $article = $articleRepository->find($data['articleId']);
        if (!$article) {
            return $this->json(['error' => 'Article not found'], 404);
        }

        // Vérifier si l'article est déjà dans le panier
        $existingBasketEntry = $basketEntryRepository->findOneBy([
            'basket' => $basket,
            'article' => $article
        ]);

        if ($existingBasketEntry) {
            // Incrémenter la quantité si l'article existe déjà
            $existingBasketEntry->setQuantity($existingBasketEntry->getQuantity() + $data['quantity']);
            $basketEntry = $existingBasketEntry;
        } else {
            // Créer une nouvelle ligne de panier
            $basketEntry = new BasketEntry();
            $basketEntry->setBasket($basket);
            $basketEntry->setArticle($article);
            $basketEntry->setQuantity($data['quantity']);
            $basketEntry->setItemCost($article->getPrice());

            $entityManager->persist($basketEntry);
        }

        $entityManager->flush();

        return $this->json([
            'id' => $basketEntry->getId(),
            'articleId' => $article->getId(),
            'articleName' => $article->getName(),
            'quantity' => $basketEntry->getQuantity(),
            'itemCost' => $basketEntry->getItemCost(),
            'totalPrice' => $basketEntry->getQuantity() * $basketEntry->getItemCost()
        ], 201);
    }

    #[Route('/api/basket/{basketId}/remove-article/{articleId}', name: 'api_basket_remove_article', methods: ['DELETE'])]
    public function removeFromBasket(
        int $basketId,
        int $articleId,
        BasketRepository $basketRepository,
        ArticleRepository $articleRepository,
        BasketEntryRepository $basketEntryRepository,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        $basket = $basketRepository->find($basketId);
        if (!$basket) {
            return $this->json(['error' => 'Basket not found'], 404);
        }

        $article = $articleRepository->find($articleId);
        if (!$article) {
            return $this->json(['error' => 'Article not found'], 404);
        }

        $basketEntry = $basketEntryRepository->findOneBy([
            'basket' => $basket,
            'article' => $article
        ]);

        if (!$basketEntry) {
            return $this->json(['error' => 'Article not in basket'], 404);
        }

        $entityManager->remove($basketEntry);
        $entityManager->flush();

        return $this->json(['success' => true], 200);
    }

    #[Route('/api/basket/{basketId}/validate', name: 'api_basket_validate', methods: ['POST'])]
    public function validateBasket(
        int $basketId,
        BasketRepository $basketRepository,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        $basket = $basketRepository->find($basketId);
        if (!$basket) {
            return $this->json(['error' => 'Basket not found'], 404);
        }

        $basket->setStatus('validated');
        $entityManager->flush();

        return $this->json([
            'id' => $basket->getId(),
            'status' => $basket->getStatus()
        ], 200);
    }
}
