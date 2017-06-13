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
                'id' => $article_id,
            )));
        }

        return $this->render('Comment/form.html.twig', array(
            'comment' => $comment,
            'form' => $form->createView(),
            'id' => $article_id));
    }
}
