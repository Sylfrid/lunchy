<?php

namespace Tools\Bundle\UserBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;

class RegistrationFormType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        // Ajoutez vos champs ici, revoilà notre champ *location* :
        $builder->add('firstname' ,'text', array('label'  => 'Nom'))
                ->add('lastname' ,'text', array('label'  => 'Prénom'));
    }

    public function getName()
    {
        return 'tools_user_registration';
    }
}