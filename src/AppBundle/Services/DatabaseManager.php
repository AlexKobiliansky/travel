<?php

namespace AppBundle\Services;

use AppBundle\Entity\Article;

class DatabaseManager
{
    protected $em;

    public function __construct(\Doctrine\ORM\EntityManager $em)
    {
        $this->em = $em;
    }

    public function create($object)
    {
        if ($object instanceof Article) {
            $object->setDateCreated(new \DateTime("now"));

            $this->em->persist($object);
            $this->em->flush();
        }
    }
}
