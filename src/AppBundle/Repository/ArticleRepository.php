<?php

namespace AppBundle\Repository;

use Doctrine\ORM\Tools\Pagination\Paginator;

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
