<?php
namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Form\CommentType;
use AppBundle\Entity\Comment;
use AppBundle\Entity\User;

/**
 * Class CommentController
 * @package AppBundle\Controller
 * @Route("/comment")
 */
class CommentController extends Controller
{
    /**
     * @Route ("/create/{article_id}", requirements={"article_id":"\d+"}, name="comment_create")
     *
     */

    public function createAction(Request $request, $article_id)
    {
        $comment = new Comment();

        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment = $form->getData();

            $article = $this->getDoctrine()
                ->getRepository('AppBundle:Article')
                ->find($article_id);

            $author = $this->getDoctrine()
                ->getRepository('AppBundle:User')
                ->find(rand(2, 10));

            $comment->setArticle($article);

            $comment->setAuthor($author);

            $date = new \DateTime("now");
            $comment->setDateCreated($date);
            $comment->setApproved(false);

            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();

            $form->isEmpty();

            return $this->redirect($this->generateUrl('show_article', array(
                'slug' => $article->getSlug(),
            )));
        }

        return $this->render('Comment/form.html.twig', array(
            'comment' => $comment,
            'form' => $form->createView(),
            'id' => $article_id));
    }

    /**
     * @Route ("/subcomment_create/{article_id}/{comment_id}",
     * requirements={"article_id":"\d+", "comment_id":"\d+"}, name="subcomment_create")
     */

    public function subcommentCreateAction(Request $request, $article_id, $comment_id)
    {
        $subcomment = new Comment;

        $form = $this->createForm(CommentType::class, $subcomment);

        $form->handleRequest($request);

        $article = $this->getDoctrine()
            ->getRepository('AppBundle:Article')
            ->find($article_id);

        $comment = $this->getDoctrine()
            ->getRepository('AppBundle:Comment')
            ->find($comment_id);

        if ($form->isSubmitted() && $form->isValid()) {
            //$comment = $form->getData();

            $author = $this->getDoctrine()
                ->getRepository('AppBundle:User')
                ->find(rand(2, 10));

            $subcomment->setArticle($article);

            $subcomment->setAuthor($author);

            $date = new \DateTime("now");
            $subcomment->setDateCreated($date);
            $subcomment->setParentComment($comment);
            $subcomment->setApproved(false);

            $em = $this->getDoctrine()->getManager();
            $em->persist($subcomment);
            $em->flush();

            return $this->redirect($this->generateUrl('show_article', array(
                'slug' => $article->getSlug(),
            )));
        }

        return $this->render('Comment/subcomment_form.html.twig', array(
            'article' => $article,
            'comment' => $comment,
            'form'    => $form->createView(),
        ));
    }

    /**
     * @Route ("/subcomment_show/{article_id}/{comment_id}",
     * requirements={"article_id":"\d+", "comment_id":"\d+"}, name="subcomment_show")
     */

    public function subcommentShowAction(Request $request, $article_id, $comment_id)
    {
        $article = $this->getDoctrine()
            ->getRepository('AppBundle:Article')
            ->find($article_id);

        $comment = $this->getDoctrine()
            ->getRepository('AppBundle:Comment')
            ->find($comment_id);
//dump($comment); die();
        return $this->render('Comment/subcomments.html.twig', array(
            'article' => $article,
            'comment' => $comment,
        ));
    }
}
