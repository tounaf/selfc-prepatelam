<?php

namespace Telma\Selfcare\PrepaidBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Acl\Exception\Exception;
use Telma\Selfcare\PrepaidBundle\Entity\Activity;
use Telma\Selfcare\PrepaidBundle\Entity\User;
use Telma\Selfcare\PrepaidBundle\Form\UserSearchType;
use Telma\Selfcare\PrepaidBundle\Form\UserType;
use Telma\Selfcare\PrepaidBundle\Util\Util;

class UserController extends Controller
{

    public function resetPasswordAction(Request $request)
    {
        $email = $request->request->get('username');
        $activity = new Activity();
        if ($email) {
            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository(User::class)->findOneBy(array("email" => $email));
            if ($user) {
                if ($user->getIsValid() == true) {
                    $emailTo = $user->getEmail();
                    $newPassword = Util::generatePassword();
                    $userManager = $this->container->get('fos_user.user_manager');
                    $user->setPlainPassword($newPassword);
                    $userManager->updateUser($user);
                    $message = \Swift_Message::newInstance()->setSubject("Changement mot de passe")
                        ->setFrom('send@example.com')
                        ->setTo($emailTo)
                        ->setContentType("text/html")->setBody(
                            $this->renderView(
                                'TelmaSelfcarePrepaidBundle:User:email.txt.twig',
                                array('password' => $newPassword)
                            )
                        );
                    $this->get('mailer')->send($message);
                    return $this->render('TelmaSelfcarePrepaidBundle:User:resetPassword.html.twig', array());
                }
            } else {
                $this->get('session')->getFlashBag()->add("notif", "Votre compte n ' existe pas encore dans la base ou a été désactivé.Merci de contacter votre Supérieur ");
                return $this->render('@FOSUser/Resetting/request_content.html.twig', array());
            }
        }
    }


    /**
     * Lists all SubscriptionCompany entities.
     *
     */
    public function indexAction(Request $request)
    {

        $entity = new User();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        $em = $this->getDoctrine()->getManager();
        if ($request->isMethod('POST')) {
            $userLogin = $form->getData()->getLogin();
            $userNom = $form->getData()->getNom();
            $userPrenom = $form->getData()->getPrenom();
            $userStatus = $form->getData()->getIsValid();
            $userType = $form->getData()->getIsAdmin();
            $userCompanies = $form->getData()->getCompanies();
            $userCreated = $form->getData()->getCreatedAt();
            $userList = $em->getRepository('TelmaSelfcarePrepaidBundle:User')->filterPagList(
                $userLogin,
                $userNom,
                $userPrenom,
                $userStatus,
                $userType,
                $userCompanies,
                $userCreated);
        } else {
            $userList = $em->getRepository('TelmaSelfcarePrepaidBundle:User')->findAll();
        }
//        var_dump(count($userList));die();
        $paginator = $this->get('knp_paginator');
        $entities = $paginator->paginate(
            $userList,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 10)
        );

        return $this->render('TelmaSelfcarePrepaidBundle:User:index.html.twig', array(
            'pagination' => $entities,
        ));
    }

    public function newAction()
    {
        $user = new User();
        $form = $this->createCreateForm($user);

        return $this->render('TelmaSelfcarePrepaidBundle:User:new.html.twig', array(
            'user' => $user,
            'form' => $form->createView(),
        ));
    }

    public function filterAction()
    {
        $user = new User();
        $form = $this->createForm(new UserSearchType(), $user, array(
            'action' => $this->generateUrl('user_create'),
            'method' => 'POST',
        ));
        return $this->render('TelmaSelfcarePrepaidBundle:User:form_search.html.twig', array(
            'user' => $user,
            'form' => $form->createView(),
        ));
    }

    public function createAction(Request $request)
    {
        $entity = new User();
        $password = Util::generatePassword();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $toEmail = $form->getData()->getEmail();
            $em = $this->getDoctrine()->getManager();
            $entity->setPlainPassword($password);
            $em->persist($entity);
            $em->flush();
            try {
                $message = \Swift_Message::newInstance()->setSubject("Nouveau compte")
                    ->setFrom('send@example.com')
                    ->setTo($toEmail)
                    ->setContentType("text/html")->setBody(
                        "test envoi mail"
                    );
                $this->get('mailer')->send($message);
            } catch (Exception $exception) {
                throw Exception("Erreur lors d'envoi email ");
            }

            $this->get('session')->getFlashBag()->add("create_success", "Mise à jour de l'utilisateur ".$user->getNom()." avec succès");
            return $this->redirect($this->generateUrl('company'));
        }
        return $this->render('TelmaSelfcarePrepaidBundle:User:new.html.twig', array(
            'user' => $entity,
            'form' => $form->createView(),
        ));

    }


    /**
     * Creates a form to create a Action entity.
     *
     * @param Action $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(User $entity)
    {
        $form = $this->createForm(new UserType(), $entity, array(
            'action' => $this->generateUrl('user_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Ajouter'));

        return $form;
    }


    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository('TelmaSelfcarePrepaidBundle:User')->find($id);

        if (!$user) {
            throw $this->createNotFoundException('Unable to find Action entity.');
        }

        $editForm = $this->createEditForm($user);
//        $deleteForm = $this->createDeleteForm($id);

//        var_dump($user);die();
        return $this->render('TelmaSelfcarePrepaidBundle:User:edit.html.twig', array(
            'user' => $user,
            'form' => $editForm->createView(),
//            'delete_form' => $deleteForm->createView(),
        ));

    }


    private function createEditForm(User $user)
    {
        $form = $this->createForm(new UserType(), $user, array(
            'action' => $this->generateUrl('user_update', array('id' => $user->getId())),
            'method' => 'PUT',
        ));
        $form->add('submit', 'submit', array('label' => 'Mettre à jour'));

        return $form;
    }


    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository('TelmaSelfcarePrepaidBundle:User')->find($id);

        if (!$user) {
            throw $this->createNotFoundException('Unable to find Action entity.');
        }

//        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($user);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();
            $this->get('session')->getFlashBag()->add("update_success", "Mise à jour de l'utilisateur ".$user->getNom()." avec succès");
            return $this->redirect($this->generateUrl('user'));
        }

        return array(
            'entity' => $user,
            'edit_form' => $editForm->createView(),
//            'delete_form' => $deleteForm->createView(),
        );
    }

}
