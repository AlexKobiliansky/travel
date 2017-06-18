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
     * @Route("/show/{slug}", name="show_article")
     * @ParamConverter("article", class="AppBundle:Article")
     */
    public function showAction($slug)
    {
        $em = $this->getDoctrine()->getManager();
        $article = $em->getRepository('AppBundle:Article')->findOneBySlug($slug);

        if (!$article) {
            throw $this->createNotFoundException(
                'No article found!'
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
            $this->get('app.dbManager')->create($article);

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
        $this->get('app.dbManager')->delete($article);

        return $this->redirectToRoute('homepage');
    }

    /**
     * @param Request $request
     * @Route("/update/{id}", name="article_update", requirements={"id":"\d+"} )
     * @ParamConverter("article", class="AppBundle:Article")
     */
    public function updateAction(Request $request, Article $article)
    {
        if (!$article) {
            throw $this->createNotFoundException('Unable to find Article');
        }

        $form = $this->createForm(ArticleType::class, $article, ["validation_groups" => "edit"]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->get('app.dbManager')->update($article);

            return $this->redirectToRoute('homepage');
        }

        return $this->render('Article\update.html.twig', array(
            'article' => $article,
            'form'    => $form->createView(),
        ));
    }

    /**
     * @param Request $request
     * @param $categoryId
     * @Route ("/listByCategory/{slug}", name="article_list_by_category")
     */
    public function listByCategoryAction(Request $request, $slug)
    {
        $em = $this->getDoctrine()->getManager();
        $categoryName = $em->getRepository('AppBundle:Category')->findOneBySlug($slug);

        $articles = $em->getRepository('AppBundle:Article')->findByCategory($categoryName);

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
     * @Route("/listByTag/{slug}", name="article_list_by_tag")
     */

    public function listByTagAction(Request $request, $slug)
    {
        $em = $this->getDoctrine()->getManager();
        $tagName = $em->getRepository('AppBundle:Tag')->findOneBySlug($slug);

        $articles = $em->getRepository('AppBundle:Article')->getByTag($tagName);

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
     * @Route("/listByAuthor/{slug}", name="article_list_by_author")
     */

    public function listByAuthorAction(Request $request, $slug)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository('AppBundle:User')->findOneBySlug($slug);

        $articles = $em->getRepository('AppBundle:Article')->getByAuthor($user);

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
