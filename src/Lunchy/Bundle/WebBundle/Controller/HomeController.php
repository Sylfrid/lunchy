<?php

namespace Lunchy\Bundle\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{
    public function indexAction()
    {
        return $this->render('LunchyWebBundle:Home:index.html.twig', array());
    }
}
