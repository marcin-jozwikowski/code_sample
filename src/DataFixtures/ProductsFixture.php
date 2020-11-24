<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductsFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $product = (new Product())
            ->setName('First')
            ->setQuantity(100);

        $manager->persist($product);

        $manager->flush();
    }
}
