<?php

namespace AppBundle\Form\Factory;

use FOS\UserBundle\Form\Factory\FactoryInterface;
use Symfony\Component\Form\FormFactoryInterface;

class FormFactory implements FactoryInterface
{
    private $formFactory;
    private $name;
    private $type;
    private $validationGroups;
    private $submitButtonLabel;

    public function __construct(FormFactoryInterface $formFactory, $name, $type, array $validationGroups = null, $submitButtonLabel = 'Submit')
    {
        $this->formFactory = $formFactory;
        $this->name = $name;
        $this->type = $type;
        $this->validationGroups = $validationGroups;
        $this->submitButtonLabel = $submitButtonLabel;
    }

    public function createForm()
    {
        return $this->formFactory->createNamed($this->name, $this->type, null, ['validation_groups' => $this->validationGroups])->add('submit', 'submit', ['label' => $this->submitButtonLabel, 'translation_domain' => 'FOSUserBundle']);
    }

    public function createFormWithoutSubmit()
    {
        return $this->formFactory->createNamed($this->name, $this->type, null, ['validation_groups' => $this->validationGroups]);
    }
}
