<?php

namespace AppBundle\Repository;

class ArticleRepository extends \Doctrine\ORM\EntityRepository
{
    public function getLatestArticles()
    {
        $articles = $this->createQueryBuilder('a')
            ->select('a')
            ->addOrderBy('a.dateCreated', 'DESC')
            ->getQuery()
            ->getResult();

        return $articles;
    }
}
