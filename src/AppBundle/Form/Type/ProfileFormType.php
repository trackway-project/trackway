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
        $overrideOptions = array_key_exists('override', $options) && is_array($options['override']) ? $options['override'] : [];

        $builder
            ->add('username', null, array_merge([
                'label' => 'form.username',
                'translation_domain' => 'FOSUserBundle'
            ], array_key_exists('username', $overrideOptions) ? $overrideOptions['username'] : []))
            ->add('email', 'email', array_merge([
                'label' => 'form.email',
                'translation_domain' => 'FOSUserBundle'
            ], array_key_exists('email', $overrideOptions) ? $overrideOptions['email'] : []))
            ->add('memberships', 'entity', array_merge([
                'label' => 'form.memberships',
                'translation_domain' => 'FOSUserBundle',
                'expanded'  => true,
                'multiple'  => true,
                'class' => 'AppBundle\Entity\Membership',
                //'choices' => $user->getMemberships()
            ], array_key_exists('memberships', $overrideOptions) ? $overrideOptions['memberships'] : []))
            ->add('activeTeam', 'entity', array_merge([
                'label' => 'form.activeTeam',
                'translation_domain' => 'FOSUserBundle',
                'class' => 'AppBundle\Entity\Team',
                //'choices' => $membershipRepository->findAllByUser($user)
            ], array_key_exists('activeTeam', $overrideOptions) ? $overrideOptions['activeTeam'] : []));
    }

    public function getName()
    {
        return 'appbundle_profile_form_type';
    }
}