<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class InvitationFormType
 *
 * @package AppBundle\Form\Type
 */
class InvitationFormType extends AbstractOverridableFormType
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
                'required' => true
            ], $options))
            ->add('email', 'email', $this->overrideOptions('email', [
                'trim' => true
            ], $options))
            ->add('status', 'choice', $this->overrideOptions('status', [
                'choices' => [
                    'open' => 'Open',
                    'cancelled' => 'Cancelled',
                    'accepted' => 'Accepted',
                    'rejected' => 'Rejected'
                ],
                'required' => true
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
