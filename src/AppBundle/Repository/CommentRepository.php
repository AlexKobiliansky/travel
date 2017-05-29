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

    public function getLatestComments($limit)
    {
        $qb = $this->createQueryBuilder('c')
            ->select('c')
            ->addOrderBy('c.dateCreated', 'DESC');

        if (!is_null($limit)) {
            $qb->setMaxResults($limit);
        }

        $comments = $qb->getQuery()->getResult();

        return $comments;
    }
}
