<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Form\OrderFormType;
use App\Service\CartService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    private $cartService;
    private $entityManager;

    public function __construct(CartService $cartService, EntityManagerInterface $entityManager)
    {
        $this->cartService = $cartService;
        $this->entityManager = $entityManager;
    }

    #[Route('/order/checkout', name: 'order_checkout')]
    public function checkout(Request $request): Response
    {
        $cart = $this->cartService->getCart();
        
        if ($cart->getTotalItems() === 0) {
            $this->addFlash('warning', 'Корзина пуста. Добавьте товары для оформления заказа.');
            return $this->redirectToRoute('cart_show');
        }

        $order = new Order();
        $form = $this->createForm(OrderFormType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Создаем заказ
            $order->setStatus(\App\Enum\OrderStatus::NEW);
            $order->setTotal((string)$cart->getTotalPrice());
            $order->setCreatedAt(new \DateTime());
            $order->setUpdatedAt(new \DateTime());

            // Добавляем товары из корзины в заказ
            foreach ($cart->getItems() as $cartItem) {
                $orderItem = new OrderItem();
                $orderItem->setOrder($order);
                $orderItem->setProduct($cartItem->getProduct());
                $orderItem->setQuantity($cartItem->getQuantity());
                $orderItem->setPrice($cartItem->getTotalPrice());
                $order->addOrderItem($orderItem);
            }

            $this->entityManager->persist($order);
            $this->entityManager->flush();

            // Очищаем корзину после создания заказа
            $this->cartService->clearCart();

            $this->addFlash('success', 'Заказ успешно оформлен! Номер заказа: #' . $order->getId());
            return $this->redirectToRoute('order_success', ['id' => $order->getId()]);
        }

        return $this->render('order/checkout.html.twig', [
            'form' => $form->createView(),
            'cart' => $cart,
        ]);
    }

    #[Route('/order/success/{id}', name: 'order_success')]
    public function success(Order $order): Response
    {
        return $this->render('order/success.html.twig', [
            'order' => $order,
        ]);
    }

    #[Route('/order/{id}', name: 'order_show')]
    public function show(Order $order): Response
    {
        return $this->render('order/show.html.twig', [
            'order' => $order,
        ]);
    }
}
