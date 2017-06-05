<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Tag;
use AppBundle\Form\TagType;

/**
 * Class TagController
 * @package AppBundle\Controller
 * @Route("/tag")
 */
class TagController extends Controller
{
    /**
     * @param Request $request
     * @Route("/list", name="tag_list")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $tags = $em->getRepository('AppBundle:Tag')->findAll();

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($tags, $request->query->get('page', 1), 10);

        $tag = new Tag;

        $form = $this->createForm(TagType::class, $tag);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tag = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($tag);
            $em->flush();

            return $this->redirectToRoute('tag_list');
        }

        return $this->render('Tag/list.html.twig', array(
            'tags' => $pagination,
            'form' => $form->createView(),

        ));
    }

    /**
     * @Route("/delete/{id}", name="tag_delete", requirements = {"id":"\d+"})
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $tag = $em->getRepository('AppBundle:Tag')->find($id);
        $em->remove($tag);
        $em->flush();

        return $this->redirect($this->generateUrl('tag_list'));
    }

    /**
     * @Route("/update/{id}", requirements={"id":"\d+"}, name="tag_update")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $tag = $em->getRepository('AppBundle:Tag')->find($id);

        if (!$tag) {
            throw $this->createNotFoundException('Unable to find Tag');
        }

        $form = $this->createForm(TagType::class, $tag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tag = $form->getData();

            $em->persist($tag);
            $em->flush();

            return $this->redirect($this->generateUrl('tag_list'));
        }

        return $this->render('Tag\update.html.twig', array(
            'tag' => $tag,
            'form' => $form->createView(),
        ));
    }
}
