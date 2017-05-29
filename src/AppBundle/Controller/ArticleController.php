<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

//use Symfony\Component\HttpFoundation\Response;

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
}
