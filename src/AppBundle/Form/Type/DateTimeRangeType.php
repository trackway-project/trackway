<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class DateTimeRangeType
 *
 * @package AppBundle\Form\Type
 */
class DateTimeRangeType extends AbstractOverridableFormType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date', 'date', $this->overrideOptions('date', [
                'label' => 'timeEntry.entity.date', 'required' => true, 'widget' => 'single_text'], $options))
            ->add('endsAt', 'time', $this->overrideOptions('endsAt', [
                'label' => 'timeEntry.entity.endsAt', 'required' => false, 'widget' => 'single_text'], $options))
            ->add('startsAt', 'time', $this->overrideOptions('startsAt', [
                'label' => 'timeEntry.entity.startsAt', 'required' => false, 'widget' => 'single_text'], $options));
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(['data_class' => 'AppBundle\Entity\DateTimeRange', 'override' => false]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_date_time_range_form_type';
    }
}
