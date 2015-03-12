<?php

namespace AppBundle\Form\Factory;

use AppBundle\Form\Type\OverridableFormTypeInterface;
use FOS\UserBundle\Form\Factory\FactoryInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormTypeInterface;

class FormFactory implements FactoryInterface
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var FormTypeInterface
     */
    private $formType;

    /**
     * @var string
     */
    private $name;

    /**
     * @var array
     */
    private $validationGroups;

    /**
     * @param FormFactoryInterface $formFactory
     * @param $name
     * @param $formType
     * @param array $validationGroups
     */
    public function __construct(FormFactoryInterface $formFactory, FormTypeInterface $formType, $name, array $validationGroups = null)
    {
        $this->formFactory = $formFactory;
        $this->formType = $formType;
        $this->name = $name;
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
            'validation_groups' => $this->validationGroups
        ];

        dump(false);

        if ($this->formType instanceof OverridableFormTypeInterface) {
            $formOptions['override'] = count($options) ? $options : false;
        }

        return $this->formFactory->createNamed($this->name, $this->formType, null, $formOptions);
    }
}
