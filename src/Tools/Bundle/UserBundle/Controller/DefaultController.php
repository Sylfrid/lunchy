<?php

namespace Tools\Bundle\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('ToolsUserBundle:Default:index.html.twig', array('name' => $name));
    }
}
