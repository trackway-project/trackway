<?php

namespace AppBundle\Form\Factory;

use AppBundle\Form\Type\OverridableFormTypeInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormTypeInterface;

class FormFactory
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
     * @param FormFactoryInterface $formFactory
     * @param $name
     * @param $formType
     */
    public function __construct(FormFactoryInterface $formFactory, FormTypeInterface $formType, $name)
    {
        $this->formFactory = $formFactory;
        $this->formType = $formType;
        $this->name = $name;
    }

    /**
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createForm(array $options = [])
    {
        $submitOptions = array_merge(['label' => 'Submit'], array_key_exists('submit', $options) ? $options['submit'] : []);

        return $this->createFormWithoutSubmit($options)->add('submit', 'submit', $submitOptions);
    }

    /**
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createFormWithoutSubmit(array $options = [])
    {
        $formOptions = [];

        if ($this->formType instanceof OverridableFormTypeInterface) {
            $formOptions['override'] = count($options) ? $options : false;
        }

        return $this->formFactory->createNamed($this->name, $this->formType, null, $formOptions);
    }
}
