<?php

namespace Telma\Selfcare\PrepaidBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Response;
use Telma\Selfcare\PrepaidBundle\Entity\Company;
use Telma\Selfcare\PrepaidBundle\Entity\UserCompanyUpdate;
use Telma\Selfcare\PrepaidBundle\Form\CompanyType;

/**
 * Company controller.
 *
 */
class CompanyController extends Controller
{

    /**
     * Lists all Company entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $companyName = $request->get('nomComp');
        $status = $request->get('statusComp');
        $debutDate = $request->get('debutDateSearch');
        $endDate = $request->get('endDateSearch');
        $companies = $em->getRepository('TelmaSelfcarePrepaidBundle:Company')->filter($companyName, $status, $debutDate, $endDate);
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate($companies,
            $request->query->getInt('page', 0),
            $request->query->getInt('limit', 5)
        );
        if($debutDate > $endDate and $request->isMethod('GET')) {
            $this->get('session')->getFlashBag()->add("invalid_date", "La date de debut doit inférieur ou égal à la date fin ");
            return $this->render('TelmaSelfcarePrepaidBundle:Company:index.html.twig', array('pagination' => $pagination));
        } else {
            return $this->render('TelmaSelfcarePrepaidBundle:Company:index.html.twig', array('pagination' => $pagination));
        }
    }

    /**
     * Creates a new Company company.
     *
     */
    public function createAction(Request $request)
    {
        if (is_object($this->get('security.context')->getToken()->getUser())) {
            $user = $this->getUser();
            if ($user->getIsAdmin() == 1) {
                $nomComp = $request->get('nomComp');
                $adressComp = $request->get('adressComp');
                $exitComp = $this->getDoctrine()->getManager()->getRepository('TelmaSelfcarePrepaidBundle:Company')->findOneBy(array('companyName' => $nomComp));
                if ($exitComp) {
                    $this->get('session')->getFlashBag()->add("existe_company", "Ce nom existe déjà en base");
                    return $this->redirectToRoute('company_new');
                } else {
                    $company = new Company();
                    $company->setUserCreation($user);
                    $company->setCompanyName($nomComp);
                    $company->setAdresse($adressComp);
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($company);
                    $em->flush();
                    return $this->redirect($this->generateUrl('company'));
                }
            }
        } else {
            $this->get('session')->getFlashBag()->add("denied", "Acces refué ");
            return $this->redirectToRoute('fos_user_security_login', array());
        }
    }

    /**
     * Creates a form to create a Company $company.
     *
     * @param Company $company The company
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Company $company)
    {
        $form = $this->createForm(new CompanyType(), $company, array(
            'action' => $this->generateUrl('company_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Company company.
     *
     */
    public function newAction()
    {
        $user = $this->getUser();
//        var_dump($user);die();
        $company = new Company();
        $form   = $this->createCreateForm($company);

        return $this->render('TelmaSelfcarePrepaidBundle:Company:new.html.twig', array(
            'company' => $company,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Company company.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $company = $em->getRepository('TelmaSelfcarePrepaidBundle:Company')->find($id);

        if (!$company) {
            throw $this->createNotFoundException('Unable to find Company entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('TelmaSelfcarePrepaidBundle:Company:index.html.twig', array(
            'company'      => $company,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Company company.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $company = $em->getRepository('TelmaSelfcarePrepaidBundle:Company')->find($id);

        if (!$company) {
            throw $this->createNotFoundException('Unable to find Company entity.');
        }

        $editForm = $this->createEditForm($company);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('TelmaSelfcarePrepaidBundle:Company:edit.html.twig', array(
            'company'      => $company,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Company company.
    *
    * @param Company $company The company
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Company $company)
    {
        $form = $this->createForm(new CompanyType(), $company, array(
            'action' => $this->generateUrl('company_update', array('id' => $company->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing Company company.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $company = $em->getRepository('TelmaSelfcarePrepaidBundle:Company')->find($id);
        if (!$company) {
            throw $this->createNotFoundException('Unable to find Company company.');
        }
        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($company);
        $editForm->handleRequest($request);
        if ($editForm->isValid()) {
            $company->setUserUpdate($this->getUser());
            $company->setLastUpdate(new \DateTime());
            $em->flush();
            return $this->render('TelmaSelfcarePrepaidBundle:Company:index.html.twig', array());
            return $this->redirect($this->generateUrl('company_edit', array('id' => $id)));
        }
        return $this->render('TelmaSelfcarePrepaidBundle:Company:edit.html.twig', array(
            'company'      => $company,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Company company.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid() || $request->isMethod('GET')) {
            $em = $this->getDoctrine()->getManager();
            $company = $em->getRepository('TelmaSelfcarePrepaidBundle:Company')->find($id);

            if (!$company) {
                throw $this->createNotFoundException('Unable to find Company company.');
            }
            $company->setStatus(false);
//            $em->remove($company);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('company'));
    }

    /**
     * Creates a form to delete a Company company by id.
     *
     * @param mixed $id The company id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('company_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }

    public function filterAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $companyName = $request->get('nomComp');
        $status = $request->get('statusComp');
        $debutDate = $request->get('debutDateSearch');
        $endDate = $request->get('endDateSearch');
        $companies = $em->getRepository('TelmaSelfcarePrepaidBundle:Company')->filter($companyName, $status, $debutDate, $endDate);
//        return $this->render('TelmaSelfcarePrepaidBundle:Company:filter_result.html.twig', array(
//            'companies'      => $companies
//        ));

//        $em = $this->getDoctrine()->getManager();
//        $query = $em->getRepository('PocsBundle:Article')
//            ->findAll();
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate($companies,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 3));

        // parameters to template
        return $this->render('TelmaSelfcarePrepaidBundle:Company:index.html.twig', array('pagination' => $pagination));


    }
}
