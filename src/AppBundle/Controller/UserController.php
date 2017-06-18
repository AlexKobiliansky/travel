<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\User;
use AppBundle\Form\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

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
            $this->get('app.dbManager')->create($user);

            return $this->redirectToRoute('user_list');
        }

        return $this->render('User/create.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/delete/{id}", name="user_delete", requirements={"id":"\d+"})
     * @ParamConverter("user", class="AppBundle:User")
     */
    public function deleteAction(User $user)
    {
        $this->get('app.dbManager')->delete($user);

        return $this->redirectToRoute('user_list');
    }

    /**
     * @param Request $request
     * @Route("/update/{id}", name="user_update", requirements={"id":"\d+"} )
     * @ParamConverter("user", class="AppBundle:User")
     */
    public function updateAction(Request $request, User $user)
    {
        if (!$user) {
            throw $this->createNotFoundException('Unable to find User');
        }

        $form = $this->createForm(UserType::class, $user, ["validation_groups" => "edit"]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->get('app.dbManager')->update($user);

            return $this->redirectToRoute('user_list');
        }

        return $this->render('User\update.html.twig', array(
            'user' => $user,
            'form' => $form->createView(),
        ));
    }
}
