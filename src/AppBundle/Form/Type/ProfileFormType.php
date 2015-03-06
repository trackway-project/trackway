<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;

class ProfileFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->remove('current_password')->add('memberships')->add('activeTeam')->add('current_password', 'password', ['label' => 'form.current_password', 'translation_domain' => 'FOSUserBundle', 'mapped' => false, 'constraints' => new UserPassword()]);
    }

    public function getParent()
    {
        return 'fos_user_profile';
    }

    public function getName()
    {
        return 'appbundle_profile_form_type';
    }
}