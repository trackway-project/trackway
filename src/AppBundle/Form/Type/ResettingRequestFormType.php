<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ResettingRequestFormType
 *
 * @package AppBundle\Form\Type
 */
class ResettingRequestFormType extends AbstractOverridableFormType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('email', EmailType::class, $this->overrideOptions('email', ['label' => 'user.entity.email', 'required' => true], $options));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => 'AppBundle\Entity\User', 'override' => false, 'validation_groups' => ['resetting']]);
    }
}
