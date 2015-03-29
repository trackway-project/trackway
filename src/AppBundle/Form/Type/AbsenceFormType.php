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
            ->add('dateTimeRange', 'appbundle_date_time_range_form_type', $this->overrideOptions('dateTimeRange', [
                'label' => '', 'required' => true], $options))
            ->add('note', null, $this->overrideOptions('note', [
                'label' => 'absence.entity.note', 'required' => false, 'trim' => true], $options))
            ->add('reason', 'entity', $this->overrideOptions('reason', [
                'label' => 'absence.entity.reason', 'class' => 'AppBundle\Entity\AbsenceReason', 'required' => true], $options));
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
