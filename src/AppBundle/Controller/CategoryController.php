<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\CategoryType;
use AppBundle\Entity\Category;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

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
            $this->get('app.dbManager')->create($category);

            return $this->redirectToRoute('category_list');
        }

        return $this->render('Category/list.html.twig', array(
            'categories' => $pagination,
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/delete/{id}", name="category_delete", requirements = {"id":"\d+"})
     * @ParamConverter("category", class="AppBundle:Category")
     */
    public function deleteAction(Category $category)
    {
        $this->get('app.dbManager')->delete($category);

        return $this->redirect($this->generateUrl('category_list'));
    }

    /**
     * @Route("/update/{id}", requirements={"id":"\d+"}, name="category_update")
     * @ParamConverter("category", class="AppBundle:Category")
     */
    public function updateAction(Request $request, Category $category)
    {
        if (!$category) {
            throw $this->createNotFoundException('Unable to find Category');
        }

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->get('app.dbManager')->update($category);

            return $this->redirect($this->generateUrl('category_list'));
        }

        return $this->render('Category\update.html.twig', array(
            'category' => $category,
            'form' => $form->createView(),
        ));
    }
}
