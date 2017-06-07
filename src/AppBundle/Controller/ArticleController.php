<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Article;
use AppBundle\Form\ArticleType;

/**
 * Class ArticleController
 * @package AppBundle\Controller
 * @Route("/article")
 */
class ArticleController extends Controller
{
    /**
     * @Route("/{id}", name="show_article", requirements={"id": "\d+"})
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $article = $em->getRepository('AppBundle:Article')
            ->find($id);

        if (!$article) {
            throw $this->createNotFoundException(
                'No article found with id '.$id
            );
        }

        $comments = $em->getRepository('AppBundle:Comment')
            ->getCommentForArticle($article->getId());

        return $this->render('Article/show.html.twig', array(
                'article' => $article,
                'comments' => $comments
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
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $article = $em->getRepository('AppBundle:Article')->find($id);
        $em->remove($article);
        $em->flush();

        return $this->redirect($this->generateUrl('homepage'));
    }

    /**
     * @param Request $request
     * @Route("/update/{id}", name="article_update", requirements={"id":"\d+"} )
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $article = $em->getRepository('AppBundle:Article')->find($id);

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
            'form' => $form->createView(),
        ));
    }
}
