<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use App\Enum\OrderStatus;
use App\Form\OrderItemFormType;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AsCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\ActionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;

#[AsCrudController]
class OrderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Order::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Заказ')
            ->setEntityLabelInPlural('Заказы')
            ->setPageTitle('index', 'Заказы')
            ->setPageTitle('new', 'Создать заказ')
            ->setPageTitle('edit', 'Редактировать заказ')
            ->setPageTitle('detail', 'Заказ')
            ->setDefaultSort(['createdAt' => 'DESC']);
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::new('setInProgress', 'В процессе')
                ->linkToCrudAction('setInProgress')
                ->setIcon('fa fa-clock')
                ->addCssClass('btn btn-warning btn-sm')
                ->displayIf(function ($entity) {
                    return $entity->getStatus() === OrderStatus::NEW;
                }))
            ->add(Crud::PAGE_INDEX, Action::new('setDelivery', 'Доставка')
                ->linkToCrudAction('setDelivery')
                ->setIcon('fa fa-truck')
                ->addCssClass('btn btn-info btn-sm')
                ->displayIf(function ($entity) {
                    return $entity->getStatus() === OrderStatus::IN_PROGRESS;
                }))
            ->add(Crud::PAGE_INDEX, Action::new('setCompleted', 'Выполнен')
                ->linkToCrudAction('setCompleted')
                ->setIcon('fa fa-check')
                ->addCssClass('btn btn-success btn-sm')
                ->displayIf(function ($entity) {
                    return $entity->getStatus() === OrderStatus::DELIVERY;
                }));
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id', 'ID')->hideOnForm(),
            TextField::new('phone', 'Телефон'),
            EmailField::new('email', 'Email'),
            TextField::new('firstName', 'Имя'),
            TextField::new('middleName', 'Отчество'),
            TextField::new('lastName', 'Фамилия'),
            TextField::new('fullName', 'Полное имя')->onlyOnIndex(),
            ChoiceField::new('status', 'Статус')
                ->setChoices([
                    'Новый' => OrderStatus::NEW,
                    'В процессе' => OrderStatus::IN_PROGRESS,
                    'Доставка' => OrderStatus::DELIVERY,
                    'Выполнен' => OrderStatus::COMPLETED,
                ])
                ->renderAsBadges([
                    OrderStatus::NEW->value => 'primary',
                    OrderStatus::IN_PROGRESS->value => 'warning',
                    OrderStatus::DELIVERY->value => 'info',
                    OrderStatus::COMPLETED->value => 'success',
                ])
                ->formatValue(function ($value, $entity) {
                    if (!$entity || !$entity->getStatus()) {
                        return 'Неизвестно';
                    }
                    return $entity->getStatus()->getLabel();
                })
                ->setTemplatePath('admin/status_badge.html.twig'),
            DateTimeField::new('createdAt', 'Создан')->hideOnForm(),
            DateTimeField::new('updatedAt', 'Обновлен')->hideOnForm(),
            CollectionField::new('orderItems', 'Позиции заказа')
                ->setEntryType(OrderItemFormType::class)
                ->setTemplatePath('admin/order_items.html.twig')
                ->hideOnIndex(),
        ];
    }

    public function setInProgress(AdminContext $context, EntityManagerInterface $entityManager, AdminUrlGenerator $adminUrlGenerator)
    {
        $entityId = $context->getRequest()->query->get('entityId');
        if (!$entityId) {
            $this->addFlash('error', 'Не удалось найти заказ');
            return $this->redirect($adminUrlGenerator->setController(self::class)->setAction('index')->generateUrl());
        }

        $order = $entityManager->find($this->getEntityFqcn(), $entityId);
        
        if (!$order) {
            $this->addFlash('error', 'Заказ не найден');
            return $this->redirect($adminUrlGenerator->setController(self::class)->setAction('index')->generateUrl());
        }

        $order->setStatus(OrderStatus::IN_PROGRESS);
        $entityManager->flush();
        
        $this->addFlash('success', 'Статус заказа изменен на "В процессе"');
        
        return $this->redirect($adminUrlGenerator->setController(self::class)->setAction('index')->generateUrl());
    }

    public function setDelivery(AdminContext $context, EntityManagerInterface $entityManager, AdminUrlGenerator $adminUrlGenerator)
    {
        $entityId = $context->getRequest()->query->get('entityId');
        if (!$entityId) {
            $this->addFlash('error', 'Не удалось найти заказ');
            return $this->redirect($adminUrlGenerator->setController(self::class)->setAction('index')->generateUrl());
        }

        $order = $entityManager->find($this->getEntityFqcn(), $entityId);
        
        if (!$order) {
            $this->addFlash('error', 'Заказ не найден');
            return $this->redirect($adminUrlGenerator->setController(self::class)->setAction('index')->generateUrl());
        }

        $order->setStatus(OrderStatus::DELIVERY);
        $entityManager->flush();
        
        $this->addFlash('success', 'Статус заказа изменен на "Доставка"');
        
        return $this->redirect($adminUrlGenerator->setController(self::class)->setAction('index')->generateUrl());
    }

    public function setCompleted(AdminContext $context, EntityManagerInterface $entityManager, AdminUrlGenerator $adminUrlGenerator)
    {
        $entityId = $context->getRequest()->query->get('entityId');
        if (!$entityId) {
            $this->addFlash('error', 'Не удалось найти заказ');
            return $this->redirect($adminUrlGenerator->setController(self::class)->setAction('index')->generateUrl());
        }

        $order = $entityManager->find($this->getEntityFqcn(), $entityId);
        
        if (!$order) {
            $this->addFlash('error', 'Заказ не найден');
            return $this->redirect($adminUrlGenerator->setController(self::class)->setAction('index')->generateUrl());
        }

        $order->setStatus(OrderStatus::COMPLETED);
        $entityManager->flush();
        
        $this->addFlash('success', 'Статус заказа изменен на "Выполнен"');
        
        return $this->redirect($adminUrlGenerator->setController(self::class)->setAction('index')->generateUrl());
    }
}
