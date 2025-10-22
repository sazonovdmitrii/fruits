<?php

namespace App\Controller;

use App\Entity\CartItem;
use App\Entity\Product;
use App\Service\CartService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    public function __construct(
        private CartService $cartService,
        private EntityManagerInterface $entityManager
    ) {}

    #[Route('/cart', name: 'cart_show', methods: ['GET'])]
    public function show(): Response
    {
        $cart = $this->cartService->getCart();

        return $this->render('cart/show.html.twig', [
            'cart' => $cart,
        ]);
    }

    #[Route('/cart/add/{id}', name: 'cart_add', methods: ['POST'])]
    public function add(Product $product, Request $request): JsonResponse
    {
        $quantity = (int) $request->request->get('quantity', 1);
        
        if ($quantity <= 0) {
            return new JsonResponse(['error' => 'Количество должно быть больше 0'], 400);
        }

        $cart = $this->cartService->addToCart($product, $quantity);
        return new JsonResponse([
            'success' => true,
            'message' => 'Товар добавлен в корзину',
            'cartItemsCount' => $this->cartService->getCartItemsCount(),
            'cartTotalPrice' => $this->cartService->getCartTotalPrice()
        ]);
    }

    #[Route('/cart/update/{id}', name: 'cart_update', methods: ['POST'])]
    public function update(CartItem $cartItem, Request $request): JsonResponse
    {
        $quantity = (int) $request->request->get('quantity', 1);
        
        $this->cartService->updateQuantity($cartItem, $quantity);
        $cart = $this->cartService->getCurrentCart();

        return new JsonResponse([
            'success' => true,
            'message' => 'Количество обновлено',
            'cartItemsCount' => $this->cartService->getCartItemsCount(),
            'cartTotalPrice' => $this->cartService->getCartTotalPrice()
        ]);
    }

    #[Route('/cart/remove/{id}', name: 'cart_remove', methods: ['POST'])]
    public function remove(CartItem $cartItem): JsonResponse
    {
        $this->cartService->removeFromCart($cartItem);
        $cart = $this->cartService->getCurrentCart();

        return new JsonResponse([
            'success' => true,
            'message' => 'Товар удален из корзины',
            'cartItemsCount' => $this->cartService->getCartItemsCount(),
            'cartTotalPrice' => $this->cartService->getCartTotalPrice()
        ]);
    }

    #[Route('/cart/clear', name: 'cart_clear', methods: ['POST'])]
    public function clear(): JsonResponse
    {
        $this->cartService->clearCart();

        return new JsonResponse([
            'success' => true,
            'message' => 'Корзина очищена',
            'cartItemsCount' => 0,
            'cartTotalPrice' => 0
        ]);
    }

    #[Route('/cart/count', name: 'cart_count', methods: ['GET'])]
    public function getCount(): JsonResponse
    {
        return new JsonResponse([
            'count' => $this->cartService->getCartItemsCount(),
            'totalPrice' => $this->cartService->getCartTotalPrice()
        ]);
    }
}
