<?php

namespace AppBundle\Form\Type;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class AbsenceFormType
 *
 * @package AppBundle\Form\Type
 */
class AbsenceFormType extends AbstractOverridableFormType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dateTimeRange', DateTimeRangeType::class, $this->overrideOptions('dateTimeRange', [
                'label' => '', 'required' => true], $options))
            ->add('note', null, $this->overrideOptions('note', [
                'label' => 'absence.entity.note', 'required' => false, 'trim' => true], $options))
            ->add('reason', EntityType::class, $this->overrideOptions('reason', [
                'label' => 'absence.entity.reason', 'class' => 'AppBundle\Entity\AbsenceReason', 'required' => true], $options));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => 'AppBundle\Entity\Absence', 'override' => false]);
    }
}
