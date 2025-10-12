<?php

namespace App\Controller\Admin;

use App\Entity\ProductImage;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;

class ProductImageCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ProductImage::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Изображение товара')
            ->setEntityLabelInPlural('Изображения товаров')
            ->setPageTitle('index', 'Изображения товаров')
            ->setPageTitle('new', 'Загрузить изображение')
            ->setPageTitle('edit', 'Редактировать изображение')
            ->setPageTitle('detail', 'Изображение')
            ->setDefaultSort(['product' => 'ASC', 'sortOrder' => 'ASC']);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id', 'ID')->hideOnForm(),
            AssociationField::new('product', 'Товар'),
            ImageField::new('filename', 'Изображение')
                ->setBasePath('/uploads/products')
                ->setUploadDir('public/uploads/products')
                ->setUploadedFileNamePattern('[randomhash].[extension]')
                ->setRequired($pageName === Crud::PAGE_NEW),
            BooleanField::new('isMain', 'Главное изображение'),
            IntegerField::new('sortOrder', 'Порядок сортировки')
                ->setHelp('Чем меньше число, тем раньше отображается')
                ->setFormTypeOption('data', 0),
        ];
    }
}

