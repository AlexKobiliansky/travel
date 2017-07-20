<?php
namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Form\CommentType;
use AppBundle\Entity\Comment;
use AppBundle\Entity\User;
use AppBundle\Entity\Article;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Class CommentController
 * @package AppBundle\Controller
 * @Route("/comment")
 */
class CommentController extends Controller
{
    /**
     * @Route ("/create/{article_id}", requirements={"article_id":"\d+"}, name="comment_create")
     * @ParamConverter("article", class="AppBundle:Article", options={"id" = "article_id"})
     */

    public function createAction(Request $request, Article $article)
    {
        $comment = new Comment();

        $this->denyAccessUnlessGranted('create', $comment);

        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        $author = $this->getUser();

        if ($form->isSubmitted() && $form->isValid()) {
            $this->get('app.dbManager')->create($comment, $article, $author);

            return $this->redirectToRoute('show_article', array(
                'slug' => $article->getSlug(),
                '_fragment' => 'comments'
            ));
        }

        return $this->render('Comment/form.html.twig', array(
            'comment' => $comment,
            'form'    => $form->createView(),
            'article' => $article));
    }

    /**
     * @Route ("/subcomment_create/{article_id}/{comment_id}",
     * requirements={"article_id":"\d+", "comment_id":"\d+"}, name="subcomment_create")
     * @ParamConverter("article", class="AppBundle:Article", options={"id" = "article_id"})
     * @ParamConverter("comment", class="AppBundle:Comment", options={"id" = "comment_id"})
     */

    public function subcommentCreateAction(Request $request, Article $article, Comment $comment)
    {
        $subcomment = new Comment;

        $this->denyAccessUnlessGranted('create', $subcomment);

        $form = $this->createForm(CommentType::class, $subcomment);

        $form->handleRequest($request);

        $author = $this->getUser();

        if ($form->isSubmitted() && $form->isValid()) {
            $subcomment->setParent($comment);

            $this->get('app.dbManager')->create($subcomment, $article, $author);

            return $this->redirectToRoute('show_article', array(
                'slug' => $article->getSlug(),
            ));
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
     * @ParamConverter("article", class="AppBundle:Article", options={"id" = "article_id"})
     * @ParamConverter("comment", class="AppBundle:Comment", options={"id" = "comment_id"})
     */

    public function subcommentShowAction(Request $request, Article $article, Comment $comment)
    {
        return $this->render('Comment/subcomments.html.twig', array(
            'article' => $article,
            'comment' => $comment,
        ));
    }

    /**
     * @Route("/delete/{id}", name="comment_delete", requirements={"id":"\d+"})
     * @ParamConverter("comment", class="AppBundle:Comment")
     */
    public function deleteAction(Request $request, Comment $comment)
    {
        $this->denyAccessUnlessGranted('delete', $comment);

        $this->get('app.dbManager')->delete($comment);

        return $this->redirectToRoute('show_article', array(
            'slug' => $comment->getArticle()->getSlug(),
        ));
    }

    /**
     * @param Request $request
     * @Route("/update/{id}", name="comment_update", requirements={"id":"\d+"} )
     * @ParamConverter("comment", class="AppBundle:Comment")
     */
    public function updateAction(Request $request, Comment $comment)
    {
        if (!$comment) {
            throw $this->createNotFoundException('Unable to find Comment');
        }

        $this->denyAccessUnlessGranted('edit', $comment);

        $form = $this->createForm(CommentType::class, $comment, ["validation_groups" => "edit"]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->get('app.dbManager')->update($comment);

            return $this->redirectToRoute('show_article', array(
                'slug' => $comment->getArticle()->getSlug()
            ));
        }

        return $this->render('Comment\update.html.twig', array(
            'comment' => $comment,
            'article' => $comment->getArticle(),
            'form'    => $form->createView(),
        ));
    }
}
