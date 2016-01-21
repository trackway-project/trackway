<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ResettingConfirmFormType
 *
 * @package AppBundle\Form\Type
 */
class ResettingConfirmFormType extends AbstractOverridableFormType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('password',
            RepeatedType::class,
            $this->overrideOptions('password',
                ['type' => PasswordType::class,
                    'invalid_message' => 'The password fields must match.',
                    'options' => ['attr' => ['class' => 'password-field']],
                    'required' => true,
                    'first_options' => ['label' => 'user.entity.password'],
                    'second_options' => ['label' => 'user.entity.passwordRepeat']],
                $options));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => 'AppBundle\Entity\User', 'override' => false, 'validation_groups' => ['change_password']]);
    }
}
