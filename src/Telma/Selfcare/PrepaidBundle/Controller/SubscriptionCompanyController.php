<?php

namespace Telma\Selfcare\PrepaidBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Telma\Selfcare\PrepaidBundle\Entity\Company;
use Telma\Selfcare\PrepaidBundle\Entity\SubscriptionCompany;
use Telma\Selfcare\PrepaidBundle\Form\SubscriptionCompanyType;

/**
 * SubscriptionCompany controller.
 *
 */
class SubscriptionCompanyController extends Controller
{

    /**
     * Lists all SubscriptionCompany entities.
     *
     */
    public function indexAction(Request $request)
    {

        $entity = new SubscriptionCompany();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        $em = $this->getDoctrine()->getManager();
        if ($request->isMethod('POST')) {
            $compId = $form->getData()->getCompany()->getId();
            $numSub = $form->getData()->getSubscription();
            $subComps = $em->getRepository('TelmaSelfcarePrepaidBundle:SubscriptionCompany')->filterSubList($compId, $numSub);
        } else {
            $subComps = $em->getRepository('TelmaSelfcarePrepaidBundle:SubscriptionCompany')->findAll();
        }
        $paginator = $this->get('knp_paginator');
        $entities = $paginator->paginate(
            $subComps,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 10)
        );

        return $this->render('TelmaSelfcarePrepaidBundle:SubscriptionCompany:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Creates a new SubscriptionCompany entity.
     *
     */
    public function createAction(Request $request)
    {

        $entity = new SubscriptionCompany();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $numSub = $form->getData()->getSubscription();
            $em = $this->getDoctrine()->getManager();
            $numAttached = $em->getRepository('TelmaSelfcarePrepaidBundle:SubscriptionCompany')->findOneBy(['subscription' => $numSub]);
            //numero existe deja
            if ($numAttached) {
                //numero deja attache
                if ($numAttached->getCompany()) {
                    $this->get('session')->getFlashBag()->add("numAttached", "Ce numéro est déjà rattaché à une entreprise");
                    return $this->redirect($this->generateUrl('subscriptioncompany'));
                } else {
                    $this->get('session')->getFlashBag()->add("numExiste", "Ce numéro existe déjà ");
                    return $this->redirect($this->generateUrl('subscriptioncompany'));
                }
            } else {
                $em->persist($entity);
                $em->flush();
                return $this->redirect($this->generateUrl('subscriptioncompany'));
            }
        }

    }

    /**
     * Creates a form to create a SubscriptionCompany entity.
     *
     * @param SubscriptionCompany $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(SubscriptionCompany $entity)
    {
        $form = $this->createForm(new SubscriptionCompanyType(), $entity, array(
            'action' => $this->generateUrl('subscriptioncompany_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new SubscriptionCompany entity.
     *
     */
    public function filterAction()
    {
        $entity = new SubscriptionCompany();
        $form = $this->createCreateForm($entity);

        return $this->render('TelmaSelfcarePrepaidBundle:SubscriptionCompany:form_filter.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }


    /**
     * Displays a form to create a new SubscriptionCompany entity.
     *
     */
    public function newAction()
    {
        $entity = new SubscriptionCompany();
        $form = $this->createCreateForm($entity);

        return $this->render('TelmaSelfcarePrepaidBundle:SubscriptionCompany:form_new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a SubscriptionCompany entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('TelmaSelfcarePrepaidBundle:SubscriptionCompany')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find SubscriptionCompany entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('TelmaSelfcarePrepaidBundle:SubscriptionCompany:index.html.twig', array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing SubscriptionCompany entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('TelmaSelfcarePrepaidBundle:SubscriptionCompany')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find SubscriptionCompany entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('TelmaSelfcarePrepaidBundle:SubscriptionCompany:edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a SubscriptionCompany entity.
     *
     * @param SubscriptionCompany $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(SubscriptionCompany $entity)
    {
        $form = $this->createForm(new SubscriptionCompanyType(), $entity, array(
            'action' => $this->generateUrl('subscriptioncompany_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing SubscriptionCompany entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('TelmaSelfcarePrepaidBundle:SubscriptionCompany')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find SubscriptionCompany entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('subscriptioncompany_edit', array('id' => $id)));
        }

        return $this->render('TelmaSelfcarePrepaidBundle:SubscriptionCompany:edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a SubscriptionCompany entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
//        die($id);
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('TelmaSelfcarePrepaidBundle:SubscriptionCompany')->find($id);
        if ($entity) {
            $entity->setCompany(null);
            $em->flush();
            return $this->redirect($this->generateUrl('subscriptioncompany'));
        }
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('TelmaSelfcarePrepaidBundle:SubscriptionCompany')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find SubscriptionCompany entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('subscriptioncompany'));
    }

    /**
     * Creates a form to delete a SubscriptionCompany entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('subscriptioncompany_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm();
    }
}
