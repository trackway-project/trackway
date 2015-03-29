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
        $builder->add('team',
            'entity',
            $this->overrideOptions('team',
                ['label' => 'invitation.entity.team', 'class' => 'AppBundle\Entity\Team', 'expanded' => true, 'required' => true],
                $options))
            ->add('email', 'email', $this->overrideOptions('email', ['label' => 'invitation.entity.email', 'trim' => true], $options))
            ->add('status',
                'entity',
                $this->overrideOptions('status', ['label' => 'invitation.entity.status', 'class' => 'AppBundle\Entity\InvitationStatus'], $options));
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(['data_class' => 'AppBundle\Entity\Invitation', 'override' => false]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_invitation_form_type';
    }
}
