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
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $companies = $em->getRepository('TelmaSelfcarePrepaidBundle:Company')->findAll();

        return $this->render('TelmaSelfcarePrepaidBundle:Company:index.html.twig', array(
            'companies' => $companies,
        ));
    }
    /**
     * Creates a new Company company.
     *
     */
    public function createAction(Request $request)
    {
        $user = $this->getUser();
        $company = new Company();
        $company->setUserCreation($user);
        $form = $this->createCreateForm($company);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($company);
            $em->flush();
            return $this->redirect($this->generateUrl('company_show', array('id' => $company->getId())));
        }
        return $this->render('TelmaSelfcarePrepaidBundle:Company:new.html.twig', array(
            'company' => $company,
            'form'   => $form->createView(),
        ));
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

        return $this->render('TelmaSelfcarePrepaidBundle:Company:show.html.twig', array(
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
        $userCompany = new UserCompanyUpdate();
        $company = $em->getRepository('TelmaSelfcarePrepaidBundle:Company')->find($id);

        if (!$company) {
            throw $this->createNotFoundException('Unable to find Company company.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($company);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
//            $userCompany->setUpdatedAt(new \DateTime());
//            $userCompany->setUserUpdate($this->getUser());
//            $userCompany->setCompanyUpdated($company);
//            $em->persist($userCompany);
            $company->setUserUpdate($this->getUser());
            $company->setLastUpdate(new \DateTime());
//            $em->persist($company);
            $em->flush();



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

        if ($form->isValid()) {
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
//        print_r($companyName);
//        print_r($status);
//        print_r($debutDate);
//        print_r($endDate);
//        die();
        $companies = $em->getRepository('TelmaSelfcarePrepaidBundle:Company')->filter($companyName, $status, $debutDate, $endDate);
        return $this->render('TelmaSelfcarePrepaidBundle:Company:filter_result.html.twig', array(
            'companies'      => $companies
        ));


    }
}
