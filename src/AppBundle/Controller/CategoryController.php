<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\CategoryType;
use AppBundle\Entity\Category;

/**
 * Class CategoryController
 * @package AppBundle\Controller
 * @Route("/category")
 */
class CategoryController extends Controller
{
    /**
     * @Route("/list", name="category_list")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $categories = $em->getRepository('AppBundle:Category')->findAll();

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($categories, $request->query->get('page', 1), 10);

        $category = new Category;

        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute('category_list');
        }

        return $this->render('Category/list.html.twig', array(
            'categories' => $pagination,
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/delete/{id}", name="category_delete", requirements = {"id":"\d+"})
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository('AppBundle:Category')->find($id);
        $em->remove($category);
        $em->flush();

        return $this->redirect($this->generateUrl('category_list'));
    }

    /**
     * @Route("/update/{id}", requirements={"id":"\d+"}, name="category_update")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository('AppBundle:Category')->find($id);

        if (!$category) {
            throw $this->createNotFoundException('Unable to find Category');
        }

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category = $form->getData();

            $em->persist($category);
            $em->flush();

            return $this->redirect($this->generateUrl('category_list'));
        }

        return $this->render('Category\update.html.twig', array(
            'category' => $category,
            'form' => $form->createView(),
        ));
    }
}
