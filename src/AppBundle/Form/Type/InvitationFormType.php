<?php

namespace AppBundle\Form\Type;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class InvitationFormType
 *
 * @package AppBundle\Form\Type
 */
class InvitationFormType extends AbstractOverridableFormType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('team',
            EntityType::class,
            $this->overrideOptions('team',
                ['label' => 'invitation.entity.team', 'class' => 'AppBundle\Entity\Team', 'expanded' => true, 'required' => true],
                $options))
            ->add('email', EmailType::class, $this->overrideOptions('email', ['label' => 'invitation.entity.email', 'trim' => true], $options))
            ->add('status',
                EntityType::class,
                $this->overrideOptions('status', ['label' => 'invitation.entity.status', 'class' => 'AppBundle\Entity\InvitationStatus'], $options));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => 'AppBundle\Entity\Invitation', 'override' => false]);
    }
}
