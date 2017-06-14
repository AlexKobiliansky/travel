<?php
namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Article;
use Faker;

class ArticleFixtures extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create();

        /**
         * creating articles. Total naumber = 40
         */
        for ($i=0; $i <= 40; $i++) {

            //getting random category identifier (for assigning category to article via setCategory Method)
            $c = rand(1, 15);

            $article = new Article();
            $article->setTitle($faker->catchPhrase);
            $article->setContent($faker->text($maxNbChars = 1000));
            $article->setDateCreated($faker->dateTimeThisYear($max = "now"));
            $article->setApproved(true);
            $article->setCategory($this->getReference("category-{$c}"));

            //getting author. Number of authors for each article - random from 1 to 3
            for ($n = 1; $n <= rand(1, 3); $n++) {

                //"$a" - author identifier.
                $a = rand(1, 10);
                $article->addUser($this->getReference("user-{$a}"));

                //"$t" - tag identifier
                $t=rand(1, 20);
                $article->addTag($this->getReference("tag-{$t}"));
            }
            $manager->persist($article);

            $this->addReference("article-{$i}", $article);
        }
        $manager->flush();
    }

    public function getOrder()
    {
        return 2;
    }
}
