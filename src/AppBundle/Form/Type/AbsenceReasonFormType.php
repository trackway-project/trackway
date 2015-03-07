<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class AbsenceReasonFormType
 *
 * @package AppBundle\Form\Type
 */
class AbsenceReasonFormType extends AbstractOverrideType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, $this->overrideOptions('name', [
                'required' => true,
                'trim' => true
            ], $options));
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\AbsenceReason',
            'override' => false
        ]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_absencereason_form_type';
    }
}
