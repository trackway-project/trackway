<?php

namespace AppBundle\Form\Factory;

use FOS\UserBundle\Form\Factory\FactoryInterface;
use Symfony\Component\Form\FormFactoryInterface;

class FormFactory implements FactoryInterface
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $type;

    /**
     * @var array
     */
    private $validationGroups;

    /**
     * @var string
     */
    private $submitButtonLabel;

    /**
     * @param FormFactoryInterface $formFactory
     * @param $name
     * @param $type
     * @param array $validationGroups
     * @param string $submitButtonLabel
     */
    public function __construct(FormFactoryInterface $formFactory, $name, $type, array $validationGroups = null, $submitButtonLabel = 'Submit')
    {
        $this->formFactory = $formFactory;
        $this->name = $name;
        $this->type = $type;
        $this->validationGroups = $validationGroups;
        $this->submitButtonLabel = $submitButtonLabel;
    }

    /**
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createForm(array $options = [])
    {
        $submitOptions = array_merge(
            ['label' => $this->submitButtonLabel, 'translation_domain' => 'FOSUserBundle'],
            array_key_exists('submit', $options) ? $options['submit'] : []
        );

        return $this->createFormWithoutSubmit($options)->add('submit', 'submit', $submitOptions);
    }

    /**
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createFormWithoutSubmit(array $options = [])
    {
        $formOptions = [
            'override' => count($options) ? $options : false,
            'validation_groups' => $this->validationGroups
        ];

        return $this->formFactory->createNamed($this->name, $this->type, null, $formOptions);
    }
}
