<?php

namespace AppBundle\Form\Type;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class MembershipFormType
 *
 * @package AppBundle\Form\Type
 */
class MembershipFormType extends AbstractOverridableFormType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('team',
            EntityType::class,
            $this->overrideOptions('team',
                ['label' => 'membership.entity.team', 'class' => 'AppBundle\Entity\Team', 'expanded' => true, 'required' => true],
                $options))
            ->add('user',
                EntityType::class,
                $this->overrideOptions('user',
                    ['label' => 'membership.entity.user', 'class' => 'AppBundle\Entity\User', 'expanded' => true, 'required' => true],
                    $options))
            ->add('group',
                EntityType::class,
                $this->overrideOptions('group',
                    ['label' => 'membership.entity.group', 'class' => 'AppBundle\Entity\Group', 'expanded' => true, 'required' => true],
                    $options));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => 'AppBundle\Entity\Membership', 'override' => false]);
    }
}
