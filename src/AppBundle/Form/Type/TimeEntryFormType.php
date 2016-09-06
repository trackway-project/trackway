<?php

namespace AppBundle\Form\Type;

use AppBundle\Entity\Project;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class TimeEntryFormType
 *
 * @package AppBundle\Form\Type
 */
class TimeEntryFormType extends AbstractOverridableFormType
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
                'label' => 'timeEntry.entity.note', 'required' => false, 'trim' => true], $options))
            ->add('project', EntityType::class, $this->overrideOptions('project', [
                'label' => 'timeEntry.entity.project',
                'class' => 'AppBundle\Entity\Project',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('p')
                        ->orderBy('p.name', 'ASC');
                },
                'choice_label' => function (Project $project) {
                    return sprintf('%s (%s)', $project->getName(), $project->getCostCenter());
                },
                'required' => true
            ], $options));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => 'AppBundle\Entity\TimeEntry', 'override' => false]);
    }
}
