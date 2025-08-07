<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class ArticleController extends AbstractController
{
    #[Route('/api/articles/{articleId}/rate', name: 'api_article_rate', methods: ['POST'])]
    public function rateArticle(
        int $articleId,
        Request $request,
        ArticleRepository $articleRepository,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['rating']) || $data['rating'] < 1 || $data['rating'] > 20) {
            return $this->json(['error' => 'Rating must be between 1 and 20'], 400);
        }

        $article = $articleRepository->find($articleId);
        if (!$article) {
            return $this->json(['error' => 'Article not found'], 404);
        }

        $article->setRating($data['rating']);
        $entityManager->flush();

        return $this->json([
            'id' => $article->getId(),
            'name' => $article->getName(),
            'rating' => $article->getRating()
        ], 200);
    }
}
