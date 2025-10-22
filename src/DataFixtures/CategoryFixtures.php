<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $categories = [
            ['name' => 'Яблоки', 'description' => 'Свежие органические яблоки различных сортов'],
            ['name' => 'Апельсины', 'description' => 'Сочные апельсины, богатые витамином C'],
            ['name' => 'Бананы', 'description' => 'Сладкие бананы, идеальные для перекуса'],
            ['name' => 'Виноград', 'description' => 'Виноград различных сортов, выращенный без химикатов'],
            ['name' => 'Груши', 'description' => 'Ароматные груши, богатые клетчаткой'],
            ['name' => 'Клубника', 'description' => 'Сладкая клубника, выращенная в экологически чистых условиях'],
        ];

        foreach ($categories as $categoryData) {
            $category = new Category();
            $category->setName($categoryData['name']);
            $category->setDescription($categoryData['description']);
            $manager->persist($category);
        }

        $manager->flush();
    }
}

