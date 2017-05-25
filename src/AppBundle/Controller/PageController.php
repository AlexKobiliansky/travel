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
    public function indexAction()
    {
        return $this->render('Page/index.html.twig');
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
}
