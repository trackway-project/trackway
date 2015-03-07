<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TimeEntryFormType extends AbstractOverrideType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $overrideOptions = array_key_exists('override', $options) && is_array($options['override']) ? $options['override'] : [];

        $builder->add('date', 'date')
            ->add('endsAt', 'time', ['required' => false])
            ->add('startsAt', 'time', ['required' => false])
            ->add('note', null, ['required' => false, 'trim' => true])
            ->add('project', null, ['required' => false])
            ->add('task', null, ['required' => false]);
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
        return 'appbundle_timeentry_form_type';
    }
}
