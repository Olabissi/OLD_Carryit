<?php

namespace Cit\TestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Cit\TestBundle\Entity\Colis;
use Cit\TestBundle\Form\ColisType;

/**
 * Colis controller.
 *
 */
class ColisController extends Controller
{
    /**
     * Lists all Colis entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('CitTestBundle:Colis')->findAll();

        return $this->render('CitTestBundle:Colis:index.html.twig', array(
            'entities' => $entities
        ));
    }

    /**
     * Finds and displays a Colis entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('CitTestBundle:Colis')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Colis entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('CitTestBundle:Colis:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),

        ));
    }

    /**
     * Displays a form to create a new Colis entity.
     *
     */
    public function newAction()
    {
        $entity = new Colis();
        $form   = $this->createForm(new ColisType(), $entity);

        return $this->render('CitTestBundle:Colis:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView()
        ));
    }

    /**
     * Creates a new Colis entity.
     *
     */
    public function createAction()
    {
        $entity  = new Colis();
        $request = $this->getRequest();
        $form    = $this->createForm(new ColisType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('colis_show', array('id' => $entity->getId())));
            
        }

        return $this->render('CitTestBundle:Colis:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView()
        ));
    }

    /**
     * Displays a form to edit an existing Colis entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('CitTestBundle:Colis')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Colis entity.');
        }

        $editForm = $this->createForm(new ColisType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('CitTestBundle:Colis:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing Colis entity.
     *
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('CitTestBundle:Colis')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Colis entity.');
        }

        $editForm   = $this->createForm(new ColisType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('colis_edit', array('id' => $id)));
        }

        return $this->render('CitTestBundle:Colis:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Colis entity.
     *
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('CitTestBundle:Colis')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Colis entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('colis'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
