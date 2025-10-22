<?php

namespace App\Service;

use App\Entity\Cart;
use App\Entity\CartItem;
use App\Entity\Product;
use App\Repository\CartRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class CartService
{
    private CartRepository $cartRepository;
    private EntityManagerInterface $entityManager;
    private RequestStack $requestStack;

    public function __construct(
        CartRepository $cartRepository,
        EntityManagerInterface $entityManager,
        RequestStack $requestStack
    ) {
        $this->cartRepository = $cartRepository;
        $this->entityManager = $entityManager;
        $this->requestStack = $requestStack;
    }

    public function getCart(): Cart
    {
        $session = $this->requestStack->getSession();
        $sessionId = $session->getId();

        $cart = $this->cartRepository->findOneBy(['sessionId' => $sessionId]);

        if (!$cart) {
            $cart = new Cart();
            $cart->setSessionId($sessionId);
            $this->entityManager->persist($cart);
            $this->entityManager->flush();
        }

        return $cart;
    }

    public function addToCart(Product $product, int $quantity = 1): Cart
    {
        $cart = $this->getCart();

        // Проверяем, есть ли уже такой товар в корзине
        $existingItem = null;
        foreach ($cart->getItems() as $item) {
            if ($item->getProduct()->getId() === $product->getId()) {
                $existingItem = $item;
                break;
            }
        }

        if ($existingItem) {
            // Увеличиваем количество существующего товара
            $existingItem->setQuantity($existingItem->getQuantity() + $quantity);
        } else {
            // Создаем новый элемент корзины
            $cartItem = new CartItem();
            $cartItem->setProduct($product);
            $cartItem->setQuantity($quantity);
            $cart->addItem($cartItem);
        }

        $cart->setUpdatedAt(new \DateTimeImmutable());
        $this->entityManager->flush();

        return $cart;
    }

    public function updateQuantity(CartItem $cartItem, int $quantity): void
    {
        if ($quantity <= 0) {
            $this->removeFromCart($cartItem);
            return;
        }

        $cartItem->setQuantity($quantity);
        $cartItem->getCart()->setUpdatedAt(new \DateTimeImmutable());
        $this->entityManager->flush();
    }

    public function removeFromCart(CartItem $cartItem): void
    {
        $cart = $cartItem->getCart();
        $cart->removeItem($cartItem);
        $cart->setUpdatedAt(new \DateTimeImmutable());
        $this->entityManager->remove($cartItem);
        $this->entityManager->flush();
    }

    public function clearCart(): void
    {
        $cart = $this->getCart();
        foreach ($cart->getItems() as $item) {
            $this->entityManager->remove($item);
        }
        $cart->setUpdatedAt(new \DateTimeImmutable());
        $this->entityManager->flush();
    }

    public function getCartItemsCount(): int
    {
        return $this->getCart()->getTotalItems();
    }

    public function getCartTotalPrice(): float
    {
        return $this->getCart()->getTotalPrice();
    }
}

