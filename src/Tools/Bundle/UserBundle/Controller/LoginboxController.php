<?php

namespace Tools\Bundle\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class LoginboxController extends Controller
{
    public function loginincludeAction()
    {
        $csrfToken = $this->container->get('form.csrf_provider')->generateCsrfToken('authenticate');
        return $this->container->get('templating')->renderResponse('ToolsUserBundle:Security:logininclude.html.twig', array(            
            'error'         => null,
            'csrf_token'   => $csrfToken
        ));
    }
}
