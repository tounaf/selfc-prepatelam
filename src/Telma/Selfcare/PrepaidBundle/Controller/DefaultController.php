<?php

namespace Telma\Selfcare\PrepaidBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('TelmaSelfcarePrepaidBundle:Default:index.html.twig');
    }
}
