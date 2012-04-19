<?php

namespace Cit\TestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Cit\TestBundle\Entity\Voyage;
use Cit\TestBundle\Form\VoyageType;

/**
 * Voyage controller.
 *
 */
class VoyageController extends Controller
{
    /**
     * Lists all Voyage entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('CitTestBundle:Voyage')->findAll();

        return $this->render('CitTestBundle:Voyage:index.html.twig', array(
            'entities' => $entities
        ));
    }

    /**
     * Finds and displays a Voyage entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('CitTestBundle:Voyage')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Voyage entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('CitTestBundle:Voyage:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),

        ));
    }

    /**
     * Displays a form to create a new Voyage entity.
     *
     */
    public function newAction()
    {
        $entity = new Voyage();
        $form   = $this->createForm(new VoyageType(), $entity);

        return $this->render('CitTestBundle:Voyage:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView()
        ));
    }

    /**
     * Creates a new Voyage entity.
     *
     */
    public function createAction()
    {
        $entity  = new Voyage();
        $request = $this->getRequest();
        $form    = $this->createForm(new VoyageType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('voyage_show', array('id' => $entity->getId())));
            
        }

        return $this->render('CitTestBundle:Voyage:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView()
        ));
    }

    /**
     * Displays a form to edit an existing Voyage entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('CitTestBundle:Voyage')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Voyage entity.');
        }

        $editForm = $this->createForm(new VoyageType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('CitTestBundle:Voyage:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing Voyage entity.
     *
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('CitTestBundle:Voyage')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Voyage entity.');
        }

        $editForm   = $this->createForm(new VoyageType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('voyage_edit', array('id' => $id)));
        }

        return $this->render('CitTestBundle:Voyage:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Voyage entity.
     *
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('CitTestBundle:Voyage')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Voyage entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('voyage'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
