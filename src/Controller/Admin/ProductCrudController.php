<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AsCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;

#[AsCrudController]
class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Товар')
            ->setEntityLabelInPlural('Товары')
            ->setPageTitle('index', 'Товары')
            ->setPageTitle('new', 'Создать товар')
            ->setPageTitle('edit', 'Редактировать товар')
            ->setPageTitle('detail', 'Товар');
    }

    public function configureFields(string $pageName): iterable
    {
        $mainImageField = ImageField::new('mainImage.filename', 'Изображение')
            ->setBasePath('/uploads/products')
            ->onlyOnIndex();

        return [
            IdField::new('id', 'ID')->hideOnForm(),
            $mainImageField,
            TextField::new('name', 'Название'),
            MoneyField::new('price', 'Цена')
                ->setCurrency('RUB')
                ->setStoredAsCents(false)
                ->setNumDecimals(2),
            TextareaField::new('description', 'Описание')->hideOnIndex(),
            AssociationField::new('category', 'Категория'),
            AssociationField::new('units', 'Единицы измерения')
                ->setFormTypeOptions([
                    'by_reference' => false,
                ])
                ->hideOnIndex(),
            AssociationField::new('images', 'Изображения')
                ->onlyOnDetail()
                ->setTemplatePath('admin/product_images.html.twig'),
        ];
    }
}
