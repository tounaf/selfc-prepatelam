<?php

namespace Telma\Selfcare\PrepaidBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Telma\Selfcare\PrepaidBundle\Entity\User;

class UserController extends Controller
{
    public function resetPasswordAction(Request $request)
    {
        $email = $request->request->get('username');
        if ($email){
            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository(User::class)->findOneBy(array("email" => $email));
            if ($user) {
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
//                $mailer->send($message);

            }
        }


        return $this->render('TelmaSelfcarePrepaidBundle:User:resetPassword.html.twig', array());
    }

}
