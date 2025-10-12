<?php

namespace App\Controller\Admin;

use App\Entity\Unit;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class UnitCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Unit::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Единица измерения')
            ->setEntityLabelInPlural('Единицы измерения')
            ->setPageTitle('index', 'Единицы измерения')
            ->setPageTitle('new', 'Создать единицу измерения')
            ->setPageTitle('edit', 'Редактировать единицу измерения')
            ->setPageTitle('detail', 'Единица измерения');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id', 'ID')->hideOnForm(),
            TextField::new('name', 'Название'),
            TextField::new('shortName', 'Сокращение'),
            AssociationField::new('products', 'Товары')->onlyOnDetail()
        ];
    }
}

