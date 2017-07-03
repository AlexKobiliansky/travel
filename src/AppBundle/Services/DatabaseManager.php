<?php

namespace AppBundle\Services;

use AppBundle\Entity\Article;
use AppBundle\Entity\Comment;
use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Controller\SecurityController;

class DatabaseManager
{
    protected $em;

    public function __construct(\Doctrine\ORM\EntityManager $em)
    {
        $this->em = $em;
    }

    public function create($object, $article = null, $author = null)
    {
        if ($object instanceof Article) {
            $object->setDateCreated(new \DateTime("now"));

            $this->em->persist($object);
            $this->em->flush();
        } elseif ($object instanceof Comment) {
            $object->setArticle($article);
            $object->setAuthor($author);
            $object->setDateCreated(new \DateTime("now"));

            $this->em->persist($object);
            $this->em->flush();
        } elseif ($object instanceof User) {
            $object->setEnabled(true);
            $object->setRoles(array('ROLE_AUTHOR'));

            $this->em->persist($object);
            $this->em->flush();
        } else {
            $this->em->persist($object);
            $this->em->flush();
        }
    }

    public function delete($object)
    {
        $this->em->remove($object);
        $this->em->flush();
    }

    public function update($object)
    {
        if ($object instanceof Article || $object instanceof Comment) {
            $object->setDateUpdated(new \DateTime("now"));

            $this->em->persist($object);
            $this->em->flush();
        } else {
            $this->em->persist($object);
            $this->em->flush();
        }
    }
}
