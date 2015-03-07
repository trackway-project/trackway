<?php

namespace AppBundle\Form\Type;

use AppBundle\Entity\Repository\MembershipRepository;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use FOS\UserBundle\Form\Type\ProfileFormType as BaseFormType;

class ProfileFormType extends BaseFormType
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