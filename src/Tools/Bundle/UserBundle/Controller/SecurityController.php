<?php

namespace Tools\Bundle\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SecurityController extends Controller
{
    public function loginincludeAction()
    {
        $csrfToken = $this->container->get('form.csrf_provider')->generateCsrfToken('authenticate');
        return $this->container->get('templating')->renderResponse('ToolsUserBundle:Security:logininclude.html.twig', array(            
            'error'         => null
        ));
    }
    
    public function loginAction()
    {
        return $this->container->get('templating')->renderResponse('ToolsUserBundle:Default:mustlog.html.twig', array());
    }
}
