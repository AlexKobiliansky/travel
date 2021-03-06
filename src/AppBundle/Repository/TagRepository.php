<?php

namespace AppBundle\Repository;

/**
 * TagRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TagRepository extends \Doctrine\ORM\EntityRepository
{
    public function getTags($number)
    {
        $tags = $this->createQueryBuilder('t')
            ->select('t')
            ->getQuery()
            ->getResult();

        uksort($tags, function () {
            return rand() > rand();
        });

        $tagCloud = array_slice($tags, 0, $number);

        return $tagCloud;
    }
}
