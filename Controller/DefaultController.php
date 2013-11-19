<?php

namespace Teneleven\Bundle\CareerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('TenelevenCareerBundle:Default:index.html.twig', array('name' => $name));
    }
}
