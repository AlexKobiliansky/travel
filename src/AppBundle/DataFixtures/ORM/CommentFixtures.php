<?php
namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Comment;
use Faker;

class CommentFixtures extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create();
        //creating comments. Total number of comments = 150
        for ($i = 1; $i <= 150; $i++) {
            //"$a" - article identifier
            $a = rand(1, 40);

            //"$b" - author identifier
            $b = rand(1, 10);

            $comment = new Comment();
            $comment->setContent($faker->text($maxNbChars = 100));
            $comment->setDateCreated($faker->dateTimeThisYear($max = "now"));
            $comment->setApproved(true);
            $comment->setAuthor($manager->merge($this->getReference("user-{$b}")));
            $comment->setArticle($manager->merge($this->getReference("article-{$a}")));

            $manager->persist($comment);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 3;
    }
}
