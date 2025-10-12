<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Entity\Order;
use App\Entity\Product;
use App\Entity\ProductImage;
use App\Entity\Unit;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Административная панель')
            ->setFaviconPath('favicon.svg')
            ->setTranslationDomain('EasyAdminBundle')
            ->setTextDirection('ltr')
            ->renderContentMaximized()
            ->disableDarkMode()
            ->setDefaultColorScheme('light')
            ->generateRelativeUrls()
            ;
    }

    public function index(): Response
    {
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(ProductCrudController::class)->generateUrl());
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Панель управления', 'fa fa-home');
        yield MenuItem::linkToCrud('Категории', 'fa fa-list', Category::class);
        yield MenuItem::linkToCrud('Единицы измерения', 'fa fa-balance-scale', Unit::class);
        yield MenuItem::linkToCrud('Товары', 'fa fa-shopping-cart', Product::class);
        yield MenuItem::linkToCrud('Изображения товаров', 'fa fa-image', ProductImage::class);
        yield MenuItem::linkToCrud('Заказы', 'fa fa-file-text', Order::class);
    }
}
