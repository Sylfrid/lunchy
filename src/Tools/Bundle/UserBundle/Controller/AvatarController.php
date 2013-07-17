<?php

namespace Tools\Bundle\UserBundle\Controller;


use Tools\Bundle\UserBundle\Form\Type\AvatarFormType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Form\Handler\ProfileFormHandler;

class AvatarController extends Controller
{
    public function avatarAction(){
        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }   
        
        $form = $this->createForm(new AvatarFormType, $user);
        $formHandler = new ProfileFormHandler($form, $this->getRequest(), $this->get('fos_user.user_manager'));
        $process = $formHandler->process($user, true);
        if ($process) {
            $this->get('session')->setFlash('fos_user_success', 'profile.flash.updated');
            return new RedirectResponse($this->get('router')->generate('fos_user_profile_show'));
        }   
        
        return $this->render('ToolsUserBundle:Profile:edit_avatar.html.twig', array('form' => $form->createView()));
    }
    
    public function avatarfbimportAction(){
        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }
        if(is_null($user->getFacebookURL()))
        {
            $response = new Response('URL de la page Facebook inexistante !');
            return $response;
        }
        if($user->copyfbAvatar()){
            $userManager = $this->container->get('fos_user.user_manager');
            $userManager->updateUser($user);
            $this->get('session')->setFlash('fos_user_success', 'profile.flash.updated');
            //return new RedirectResponse($this->get('router')->generate('fos_user_profile_show'));
        } else {
            $this->get('session')->setFlash('fos_user_error', 'Erreur de chargement de la photo de profil Facebook');
        }
        
        return $this->forward("FOSUserBundle:Profile:edit");
        //return $this->render('CookbookUserBundle:Profile:edit_avatar.html.twig', array('form' => $form->createView()));
    }

}
