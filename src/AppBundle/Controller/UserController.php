<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\User;
use AppBundle\Form\UserType;

/**
 * Class UserController
 * @package AppBundle\Controller
 * @Route("/user")
 */
class UserController extends Controller
{
    /**
     * @param Request $request
     * @Route("/list", name="user_list")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $users = $em->getRepository('AppBundle:User')->findAll();

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($users, $request->query->get('page', 1), 10);

        return $this->render('User/list.html.twig', array(
            'users' => $pagination,

        ));
    }

    /**
     * @param Request $request
     * @Route("/create", name="user_create")
     */
    public function createAction(Request $request)
    {
        $user = new User;

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            //get uploaded avatar
            $file = $form['avatar']->getData();

            //try to quess extension of uploaded file
            $extension = $file->guessExtension();
            if (!$extension) {
                $extension = 'jpg';
            }

            $fileName = 'User_'.$form['login']->getData().'.'.$extension;
            $file->move('avatars', $fileName);

            $user = $form->getData();
            $user->setAvatar($fileName);

            $em = $this->getDoctrine()->getManager();

            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('user_list');
        }

        return $this->render('User/create.html.twig', array(
            'form' => $form->createView()
        ));
    }
}
