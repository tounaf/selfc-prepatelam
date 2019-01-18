<?php
/**
 * Created by PhpStorm.
 * User: Harinjatovo
 * Date: 18/01/2019
 * Time: 13:11
 */

namespace Telma\Selfcare\PrepaidBundle\EventListener;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class LoginListener
{
    private $session;
    private $requestStack;
    private $securityContext;
    private $router;
    public function __construct(ContainerInterface $container, Session $session, RequestStack $requestStack)
    {
        $this->session = $session;
        $this->router = $container->get('router');
        $this->securityContext = $container->get('security.context');
        $this->requestStack = $requestStack;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $r = $this->requestStack->getCurrentRequest()->get('_route');
        if ($r == 'fos_user_security_login') {
            if (is_object($this->securityContext->getToken()->getUser())) {
                $this->session->getFlashBag()->add('notification','Vous etes deja connecté');
                $this->session->getFlashBag()->get('notification');
                $event->setResponse(new RedirectResponse($this->router-> generate('telma_selfcare_prepaid_homepage')));
            }
        }
    }
}