<?php
namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Tag;
use Faker;

class TagFixtures extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create();

        for ($i = 1; $i <= 20; $i++) {
            $tag = new Tag();
            $tag->setName($faker->unique()->word());

            $manager->persist($tag);

            $this->addReference("tag-{$i}", $tag);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 1;
    }
}
