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
     * @param FormFactoryInterface $formFactory
     * @param $name
     * @param $type
     * @param array $validationGroups
     */
    public function __construct(FormFactoryInterface $formFactory, $name, $type, array $validationGroups = null)
    {
        $this->formFactory = $formFactory;
        $this->name = $name;
        $this->type = $type;
        $this->validationGroups = $validationGroups;
    }

    /**
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createForm(array $options = [])
    {
        $submitOptions = array_merge(
            ['label' => 'Submit'],
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
