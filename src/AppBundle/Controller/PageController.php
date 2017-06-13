<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PageController extends Controller
{
    /**
     * @Route ("/", name="homepage")
     */
    public function indexAction(Request $request, $message=null)
    {
        $em = $this->getDoctrine()->getManager();

        $articles = $em->getRepository('AppBundle:Article')->getLatestArticles();

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($articles, $request->query->get('page', 1), 5);

        return $this->render('Page/index.html.twig', array(
            'articles' => $pagination,
            'message' => $message,
        ));
    }

    /**
     * @Route ("/about", name="about")
     */
    public function aboutAction()
    {
        return $this->render('Page/about.html.twig');
    }

    /**
     * @Route ("/contact", name="contact")
     */
    public function contactAction()
    {
        return $this->render('Page/contact.html.twig');
    }

    public function sidebarAction()
    {
        $em = $this->getDoctrine()->getManager();

        $tags = $em->getRepository('AppBundle:Tag')->getTags();

        $categories = $em->getRepository('AppBundle:Category')->findAll();

        $latestComments = $em->getRepository('AppBundle:Comment')->getLatestComments(5);

        return $this->render('Page/sidebar.html.twig', array(
            'tags' => $tags,
            'categories' => $categories,
            'latestComments' => $latestComments
        ));
    }
}
