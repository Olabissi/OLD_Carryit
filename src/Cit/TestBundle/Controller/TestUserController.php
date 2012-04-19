<?php

namespace Cit\TestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Cit\TestBundle\Entity\TestUser;
use Cit\TestBundle\Form\TestUserType;

/**
 * TestUser controller.
 *
 */
class TestUserController extends Controller
{
    /**
     * Lists all TestUser entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('CitTestBundle:TestUser')->findAll();

        return $this->render('CitTestBundle:TestUser:index.html.twig', array(
            'entities' => $entities
        ));
    }

    /**
     * Finds and displays a TestUser entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('CitTestBundle:TestUser')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TestUser entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('CitTestBundle:TestUser:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),

        ));
    }

    /**
     * Displays a form to create a new TestUser entity.
     *
     */
    public function newAction()
    {
        $entity = new TestUser();
        $form   = $this->createForm(new TestUserType(), $entity);

        return $this->render('CitTestBundle:TestUser:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView()
        ));
    }

    /**
     * Creates a new TestUser entity.
     *
     */
    public function createAction()
    {
        $entity  = new TestUser();
        $request = $this->getRequest();
        $form    = $this->createForm(new TestUserType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('testuser_show', array('id' => $entity->getId())));
            
        }

        return $this->render('CitTestBundle:TestUser:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView()
        ));
    }

    /**
     * Displays a form to edit an existing TestUser entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('CitTestBundle:TestUser')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TestUser entity.');
        }

        $editForm = $this->createForm(new TestUserType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('CitTestBundle:TestUser:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing TestUser entity.
     *
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('CitTestBundle:TestUser')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TestUser entity.');
        }

        $editForm   = $this->createForm(new TestUserType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('testuser_edit', array('id' => $id)));
        }

        return $this->render('CitTestBundle:TestUser:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a TestUser entity.
     *
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('CitTestBundle:TestUser')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find TestUser entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('testuser'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
