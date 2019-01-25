<?php

namespace Telma\Selfcare\PrepaidBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Telma\Selfcare\PrepaidBundle\Entity\Activity;
use Telma\Selfcare\PrepaidBundle\Entity\User;

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
                    $newPassword = $this->getParameter('RESET_PASSWORD');
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
                    //historiser l'activité
//                    $activity->setActivityDate(new \DateTime());
//                    $userActive = $this->getUser() ? $this->getUser() : '';
//                    $activity->setUserId($userActive);
                    return $this->render('TelmaSelfcarePrepaidBundle:User:resetPassword.html.twig', array());
                }
            } else {
                $this->get('session')->getFlashBag()->add("notif", "Votre compte n ' existe pas encore dans la base ou a été désactivé.Merci de contacter votre Supérieur ");
                return $this->render('@FOSUser/Resetting/request_content.html.twig', array());
            }
        }
    }

    public function createUserAction(Request $request)
    {
        $content = $request->getContent();
        $newUser = new User();

    }

}
