<?php

namespace AppBundle\Repository;

class ArticleRepository extends \Doctrine\ORM\EntityRepository
{
    public function getLatestArticles()
    {
        $qb = $this->createQueryBuilder('a')
            ->select('a')
            ->addOrderBy('a.dateCreated', 'DESC');

        return $qb->getQuery()->getResult();
    }
}
