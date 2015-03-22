<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class TimeEntryFormType
 *
 * @package AppBundle\Form\Type
 */
class TimeEntryFormType extends AbstractOverridableFormType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date', 'date', $this->overrideOptions('date', ['label' => 'timeEntry.entity.date', 'required' => true, 'widget' => 'single_text'], $options))
            ->add('endsAt', 'time', $this->overrideOptions('endsAt', ['label' => 'timeEntry.entity.endsAt', 'required' => false, 'widget' => 'single_text'], $options))
            ->add('startsAt', 'time', $this->overrideOptions('startsAt', ['label' => 'timeEntry.entity.startsAt', 'required' => false, 'widget' => 'single_text'], $options))
            ->add('note', null, $this->overrideOptions('note', ['label' => 'timeEntry.entity.note', 'required' => false, 'trim' => true], $options))
            ->add('project', 'entity', $this->overrideOptions('project', ['label' => 'timeEntry.entity.project', 'class' => 'AppBundle\Entity\Project', 'required' => false], $options))
            ->add('task', 'entity', $this->overrideOptions('task', ['label' => 'timeEntry.entity.task', 'class' => 'AppBundle\Entity\Task', 'required' => false], $options));
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(['data_class' => 'AppBundle\Entity\TimeEntry', 'override' => false]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_time_entry_form_type';
    }
}
