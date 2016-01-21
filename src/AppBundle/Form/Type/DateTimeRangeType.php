<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class DateTimeRangeType
 *
 * @package AppBundle\Form\Type
 */
class DateTimeRangeType extends AbstractOverridableFormType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date', DateType::class, $this->overrideOptions('date', [
                'label' => 'timeEntry.entity.date', 'required' => true, 'widget' => 'single_text'], $options))
            ->add('endsAt', TimeType::class, $this->overrideOptions('endsAt', [
                'label' => 'timeEntry.entity.endsAt', 'required' => false, 'widget' => 'single_text'], $options))
            ->add('startsAt', TimeType::class, $this->overrideOptions('startsAt', [
                'label' => 'timeEntry.entity.startsAt', 'required' => false, 'widget' => 'single_text'], $options));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => 'AppBundle\Entity\DateTimeRange', 'override' => false]);
    }
}
