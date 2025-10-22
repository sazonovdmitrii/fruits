<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    #[Route('/search', name: 'search', methods: ['GET', 'POST'])]
    public function search(Request $request, ProductRepository $productRepository, CategoryRepository $categoryRepository): Response
    {
        $query = $request->query->get('q', '');
        $categoryId = $request->query->get('category', '');
        
        $products = [];
        $categories = $categoryRepository->findAll();
        
        if (!empty($query)) {
            if (!empty($categoryId)) {
                // Поиск по названию и категории
                $products = $productRepository->findBySearchAndCategory($query, $categoryId);
            } else {
                // Поиск только по названию
                $products = $productRepository->findBySearch($query);
            }
        }
        
        return $this->render('search/results.html.twig', [
            'query' => $query,
            'categoryId' => $categoryId,
            'products' => $products,
            'categories' => $categories,
        ]);
    }
    
    #[Route('/search/suggestions', name: 'search_suggestions', methods: ['GET'])]
    public function suggestions(Request $request, ProductRepository $productRepository): JsonResponse
    {
        $query = $request->query->get('q', '');
        
        if (strlen($query) < 2) {
            return new JsonResponse([]);
        }
        
        $products = $productRepository->findBySearch($query, 5);
        
        $suggestions = [];
        foreach ($products as $product) {
            $suggestions[] = [
                'id' => $product->getId(),
                'name' => $product->getName(),
                'price' => $product->getPrice(),
                'image' => $product->getMainImage() ? 
                    '/uploads/products/' . $product->getMainImage()->getFilename() : 
                    '/assets/images/product/product1.png',
                'url' => $this->generateUrl('product_show', ['id' => $product->getId()])
            ];
        }
        
        return new JsonResponse($suggestions);
    }
}

