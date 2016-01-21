<?php

namespace AppBundle\Form\Type;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class UserFormType
 *
 * @package AppBundle\Form\Type
 */
class UserFormType extends AbstractOverridableFormType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('username', null, $this->overrideOptions('username', ['label' => 'user.entity.username', 'required' => true], $options))
            ->add('email', EmailType::class, $this->overrideOptions('email', ['label' => 'user.entity.email', 'required' => true], $options))
            ->add('locale', ChoiceType::class, $this->overrideOptions('locale', ['label' => 'user.entity.locale', 'class' => 'AppBundle\Entity\Locale'], $options))
            ->add('activeTeam',
                EntityType::class,
                $this->overrideOptions('activeTeam', ['label' => 'user.entity.activeTeam', 'class' => 'AppBundle\Entity\Team'], $options))
            ->add('enabled', CheckboxType::class, $this->overrideOptions('enabled', ['label' => 'user.entity.enabled'], $options));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => 'AppBundle\Entity\User', 'override' => false]);
    }
}
