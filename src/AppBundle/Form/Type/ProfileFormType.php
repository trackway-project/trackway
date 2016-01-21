<?php

namespace AppBundle\Form\Type;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;

/**
 * Class ProfileFormType
 *
 * @package AppBundle\Form\Type
 */
class ProfileFormType extends AbstractOverridableFormType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('email', EmailType::class, $this->overrideOptions('email', ['label' => 'user.entity.email', 'required' => true], $options))
            ->add('locale', EntityType::class, $this->overrideOptions('locale', ['label' => 'user.entity.locale', 'class' => 'AppBundle\Entity\Locale'], $options))
            ->add('activeTeam',
                EntityType::class,
                $this->overrideOptions('activeTeam', ['label' => 'user.entity.activeTeam', 'class' => 'AppBundle\Entity\Team'], $options))
            ->add('currentPassword',
                PasswordType::class,
                $this->overrideOptions('currentPassword',
                    ['label' => 'user.entity.currentPassword', 'mapped' => false, 'required' => true, 'constraints' => new UserPassword()],
                    $options));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => 'AppBundle\Entity\User', 'override' => false, 'validation_groups' => ['profile']]);
    }
}
