<?php

namespace Tools\Bundle\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class AvatarFormType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {    
        $builder->add('file');
    }
    
    public function getName()
    {
        return 'tools_user_avatar';
    }
}
