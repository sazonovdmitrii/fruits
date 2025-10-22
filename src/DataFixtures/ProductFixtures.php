<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $categories = $manager->getRepository(Category::class)->findAll();
        
        if (empty($categories)) {
            return;
        }

        $products = [
            // Яблоки
            ['name' => 'Яблоки Голден', 'price' => '150.00', 'description' => 'Сладкие желтые яблоки сорта Голден', 'category' => 'Яблоки'],
            ['name' => 'Яблоки Ред Делишес', 'price' => '180.00', 'description' => 'Красные хрустящие яблоки', 'category' => 'Яблоки'],
            ['name' => 'Яблоки Гренни Смит', 'price' => '160.00', 'description' => 'Зеленые кисло-сладкие яблоки', 'category' => 'Яблоки'],
            
            // Апельсины
            ['name' => 'Апельсины Валенсия', 'price' => '200.00', 'description' => 'Сочные апельсины из Испании', 'category' => 'Апельсины'],
            ['name' => 'Апельсины Навел', 'price' => '220.00', 'description' => 'Сладкие апельсины без косточек', 'category' => 'Апельсины'],
            
            // Бананы
            ['name' => 'Бананы Кавендиш', 'price' => '120.00', 'description' => 'Сладкие желтые бананы', 'category' => 'Бананы'],
            ['name' => 'Бананы Мини', 'price' => '100.00', 'description' => 'Маленькие сладкие бананы', 'category' => 'Бананы'],
            
            // Виноград
            ['name' => 'Виноград Кишмиш', 'price' => '300.00', 'description' => 'Сладкий белый виноград без косточек', 'category' => 'Виноград'],
            ['name' => 'Виноград Кардинал', 'price' => '350.00', 'description' => 'Красный виноград с косточками', 'category' => 'Виноград'],
            
            // Груши
            ['name' => 'Груши Конференс', 'price' => '250.00', 'description' => 'Сладкие груши с нежной мякотью', 'category' => 'Груши'],
            ['name' => 'Груши Аббат', 'price' => '280.00', 'description' => 'Ароматные груши с плотной мякотью', 'category' => 'Груши'],
            
            // Клубника
            ['name' => 'Клубника Альбион', 'price' => '400.00', 'description' => 'Сладкая клубника крупного размера', 'category' => 'Клубника'],
            ['name' => 'Клубника Мармолада', 'price' => '450.00', 'description' => 'Ароматная клубника с ярким вкусом', 'category' => 'Клубника'],
        ];

        foreach ($products as $productData) {
            $category = $this->findCategoryByName($categories, $productData['category']);
            if (!$category) {
                continue;
            }

            $product = new Product();
            $product->setName($productData['name']);
            $product->setPrice($productData['price']);
            $product->setDescription($productData['description']);
            $product->setCategory($category);
            $manager->persist($product);
        }

        $manager->flush();
    }

    private function findCategoryByName(array $categories, string $name): ?Category
    {
        foreach ($categories as $category) {
            if ($category->getName() === $name) {
                return $category;
            }
        }
        return null;
    }
}

