<?php

namespace AppBundle\Form\Type;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class TeamFormType
 *
 * @package AppBundle\Form\Type
 */
class TeamFormType extends AbstractOverridableFormType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', null, $this->overrideOptions('name', ['label' => 'team.entity.name', 'required' => true, 'trim' => true], $options))
            ->add('memberships',
                EntityType::class,
                $this->overrideOptions('memberships',
                    ['label' => 'team.entity.name', 'class' => 'AppBundle\Entity\Membership', 'expanded' => true, 'multiple' => true],
                    $options));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => 'AppBundle\Entity\Team', 'override' => false]);
    }
}
