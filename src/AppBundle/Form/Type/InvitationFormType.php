<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class InvitationFormType extends AbstractOverrideType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('team', 'entity', $this->overrideOptions('team', [
                'class' => 'AppBundle\Entity\Team',
                'expanded'  => true,
                'require' => true
            ], $options))
            ->add('email', 'email', $this->overrideOptions('email', [
                'trim' => true
            ], $options));
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\Invitation',
            'override' => false
        ]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_invitation_form_type';
    }
}
