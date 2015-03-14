<?php

namespace AppBundle\Form\Type;

use FOS\UserBundle\Form\Type\ProfileFormType as BaseFormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class ProfileFormType
 *
 * @package AppBundle\Form\Type
 */
class ProfileFormType extends BaseFormType implements OverridableFormTypeInterface
{
    /**
     * @var string
     */
    protected $class;

    /**
     * @param string $class The User class name
     */
    public function __construct($class)
    {
        parent::__construct($class);
        $this->class = $class;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => $this->class,
            'intention'  => 'profile',
            'override' => false
        ]);
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    protected function buildUserForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', null, $this->overrideOptions('username', [
                'label' => 'form.username',
                'translation_domain' => 'FOSUserBundle'
            ], $options))
            ->add('email', 'email', $this->overrideOptions('email', [
                'label' => 'form.email',
                'translation_domain' => 'FOSUserBundle'
            ], $options))
            ->add('locale', 'choice', $this->overrideOptions('locale', [
                'label' => 'form.locale',
                'translation_domain' => 'FOSUserBundle',
                'choices' => [
                    null => 'locale.default',
                    'de' => 'locale.de',
                    'en' => 'locale.en'
                ],
                'required' => false
            ], $options))
            ->add('memberships', 'entity', $this->overrideOptions('memberships', [
                'label' => 'form.memberships',
                'translation_domain' => 'FOSUserBundle',
                'expanded'  => true,
                'multiple'  => true,
                'class' => 'AppBundle\Entity\Membership'
            ], $options))
            ->add('activeTeam', 'entity', $this->overrideOptions('activeTeam', [
                'label' => 'form.activeTeam',
                'translation_domain' => 'FOSUserBundle',
                'class' => 'AppBundle\Entity\Team'
            ], $options));
    }

    /**
     * @param $name
     * @param array $childOptions
     * @param array $parentOptions
     *
     * @return array
     */
    protected function overrideOptions($name, array $childOptions = [], array $parentOptions = [])
    {
        $overrideOptions = array_key_exists('override', $parentOptions) && is_array($parentOptions['override']) ? $parentOptions['override'] : [];

        return array_merge(
            $childOptions,
            array_key_exists($name, $overrideOptions) ? $overrideOptions[$name] : []
        );
    }
}