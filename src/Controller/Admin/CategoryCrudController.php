<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;

class CategoryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Category::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->hideOnForm();

        yield ImageField::new('image', 'Изображение')
            ->setBasePath('uploads/categories')
            ->setUploadDir('public/uploads/categories')
            ->setUploadedFileNamePattern('[slug]-[timestamp].[extension]')
            ->setRequired(false)
            ->setSortable(false);

        yield TextField::new('name', 'Название');

        yield TextareaField::new('description', 'Описание')
            ->hideOnIndex();

        yield AssociationField::new('products', 'Товары')
            ->onlyOnDetail()
            ->setTemplatePath('admin/category_products.html.twig');
    }
}

