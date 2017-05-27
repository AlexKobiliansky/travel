<?php
namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Category;
use Faker;

class CategoryFixtures extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create();
        /**
         * creating parents Categories (number = 5)
         */

        for ($i = 1; $i <= 5; ++$i) {
            $category = new Category();
            $category->setName($faker->unique()->word(2));
            $manager->persist($category);

            $this->addReference("category-{$i}", $category);
        }

        /**
         * creating subcategories. $i - number of parent categories. Total number of subcategories = 10
         */
        for ($n=$i; $n<=$i+10; $n++) {
            /**
             * "$p" is an identifier of parents category
             * every subcstegory we assign random parent category via method "setParent"
             */
            $p = rand(1, 5);
            $category = new Category();
            $category->setName($faker->unique()->word(2));
            $category->setParent($this->getReference("category-{$p}"));

            $this->addReference("category-{$n}", $category);

            $manager->persist($category);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 1;
    }
}
