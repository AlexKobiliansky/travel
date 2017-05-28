<?php

namespace AppBundle\Repository;

class CommentRepository extends \Doctrine\ORM\EntityRepository
{
    public function getCommentForArticle($id)
    {
        $qb = $this->createQueryBuilder('c')
            ->select('c')
            ->where('c.article = :article_id')
            ->setParameter('article_id', $id);

        return $qb->getQuery()
            ->getResult();
    }
}
