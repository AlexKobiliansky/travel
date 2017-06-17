<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Enquiry;
use AppBundle\Form\EnquiryType;

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
    public function contactAction(Request $request)
    {
        $enquiry = new Enquiry();

        $form = $this->createForm(EnquiryType::class, $enquiry);

        if ($request->isMethod($request::METHOD_POST)) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $message = \Swift_Message::newInstance()
                    ->setSubject('Contact enquiry from Alex Kobilianskiy')
                    ->setFrom('alex_kobilianskiy@bigmir.net')
                    ->setTo('alex_kobolianskiy@bigmir.net')
                    ->setBody($this->renderView('Page/contactEmail.txt.twig', array('enquiry' => $enquiry)));

                $this->get('mailer')->send($message);

                $this->get('session')->getFlashBag()->add('blogger-notice', 'Your contact enquiry was successfully sent. Thank you!');

                return $this->redirect($this->generateUrl('contact'));
            }
        }

        return $this->render('Page/contact.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function sidebarAction()
    {
        $em = $this->getDoctrine()->getManager();

        $tags = $em->getRepository('AppBundle:Tag')->getTags(20);

        $categories = $em->getRepository('AppBundle:Category')->findAll();

        $latestComments = $em->getRepository('AppBundle:Comment')->getLatestComments(5);

        return $this->render('Page/sidebar.html.twig', array(
            'tags' => $tags,
            'categories' => $categories,
            'latestComments' => $latestComments
        ));
    }
}
