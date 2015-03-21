<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class AbsenceFormType
 *
 * @package AppBundle\Form\Type
 */
class AbsenceFormType extends AbstractOverridableFormType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date', 'date', $this->overrideOptions('date', ['required' => true, 'widget' => 'single_text'], $options))
            ->add('endsAt', 'time', $this->overrideOptions('endsAt', ['required' => false, 'widget' => 'single_text'], $options))
            ->add('startsAt', 'time', $this->overrideOptions('startsAt', ['required' => false, 'widget' => 'single_text'], $options))
            ->add('note', null, $this->overrideOptions('note', ['required' => false, 'trim' => true], $options))
            ->add('reason', 'entity', $this->overrideOptions('reason', ['class' => 'AppBundle\Entity\AbsenceReason', 'required' => true], $options));
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(['data_class' => 'AppBundle\Entity\Absence', 'override' => false]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_absence_form_type';
    }
}
