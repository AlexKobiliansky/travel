<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Article;
use AppBundle\Form\ArticleType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Class ArticleController
 * @package AppBundle\Controller
 * @Route("/article")
 */
class ArticleController extends Controller
{
    /**
     * @Route("/{id}", name="show_article", requirements={"id": "\d+"})
     * @ParamConverter("article", class="AppBundle:Article")
     */
    public function showAction(Article $article)
    {
        $em = $this->getDoctrine()->getManager();

        if (!$article) {
            throw $this->createNotFoundException(
                'No article found with id '
            );
        }

        $comments = $em->getRepository('AppBundle:Comment')
            ->getCommentForArticle($article->getId());

        return $this->render('Article/show.html.twig', array(
            'article'  => $article,
            'comments' => $comments,
        ));
    }

    /**
     * @param Request $request
     * @Route("/create", name="article_create")
     */
    public function createAction(Request $request)
    {
        $article = new Article;

        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article = $form->getData();

            $date = new \DateTime("now");
            $article->setDateCreated($date);
            $article->setApproved(false);
            $em = $this->getDoctrine()->getManager();

            $em->persist($article);
            $em->flush();

            return $this->redirectToRoute('homepage');
        }

        return $this->render('Article/create.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/delete/{id}", name="article_delete", requirements={"id":"\d+"})
     * @ParamConverter("article", class="AppBundle:Article")
     */

    public function deleteAction(Article $article)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($article);
        $em->flush();

        return $this->redirect($this->generateUrl('homepage'));
    }

    /**
     * @param Request $request
     * @Route("/update/{id}", name="article_update", requirements={"id":"\d+"} )
     * @ParamConverter("article", class="AppBundle:Article")
     */
    public function updateAction(Request $request, Article $article)
    {
        $em = $this->getDoctrine()->getManager();

        if (!$article) {
            throw $this->createNotFoundException('Unable to find Article');
        }

        $form = $this->createForm(ArticleType::class, $article, ["validation_groups" => "edit"]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article = $form->getData();

            $em->persist($article);
            $em->flush();

            return $this->redirect($this->generateUrl('homepage'));
        }

        return $this->render('Article\update.html.twig', array(
            'article' => $article,
            'form'    => $form->createView(),
        ));
    }

    /**
     * @param Request $request
     * @param $categoryId
     * @Route ("/listByCategory/{categoryId}", name="article_list_by_category")
     */
    public function listByCategoryAction(Request $request, $categoryId)
    {
        $em = $this->getDoctrine()->getManager();
        $articles = $em->getRepository('AppBundle:Article')->findByCategory($categoryId);

        $categoryName = $em->getRepository('AppBundle:Category')->find($categoryId);
        $message = "Sorted by category \"$categoryName\"";

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($articles, $request->query->get('page', 1), 5);

        return $this->render('Page\index.html.twig', array(
            'articles' => $pagination,
            'message'  => $message,
        ));
    }

    /**
     * @param Request $request
     * @param $tagId
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/listByTag/{tagId}", name="article_list_by_tag")
     */

    public function listByTagAction(Request $request, $tagId)
    {
        $em = $this->getDoctrine()->getManager();
        $articles = $em->getRepository('AppBundle:Article')->getByTag($tagId);

        $tagName = $em->getRepository('AppBundle:Tag')->find($tagId);
        $message = "Sorted by tag\"$tagName\"";

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($articles, $request->query->get('page', 1), 5);

        return $this->render('Page\index.html.twig', array(
            'articles' => $pagination,
            'message'  => $message,
        ));
    }


    /**
     * @param Request $request
     * @param $userId
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/listByAuthor/{userId}", name="article_list_by_author")
     */

    public function listByAuthorAction(Request $request, $userId)
    {
        $em = $this->getDoctrine()->getManager();
        $articles = $em->getRepository('AppBundle:Article')->getByAuthor($userId);

        $user = $em->getRepository('AppBundle:User')->find($userId);
        $authorName = $user->getName(). " " . $user->getSurname();
        $message = "Sorted by author \"$authorName\"";

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($articles, $request->query->get('page', 1), 5);

        return $this->render('Page\index.html.twig', array(
            'articles' => $pagination,
            'message'  => $message,
        ));
    }
}
