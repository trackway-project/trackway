<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;

/**
 * Class AbstractOverrideType
 *
 * @package AppBundle\Form\Type
 */
abstract class AbstractOverrideType extends AbstractType
{
    /**
     * @param $name
     * @param array $childOptions
     * @param array $parentOptions
     *
     * @return array
     */
    protected function overrideOptions($name, array $childOptions = [], array $parentOptions = [])
    {
        $overrideOptions = array_key_exists('override', $parentOptions) && is_array($parentOptions['override']) ? $parentOptions['override'] : [];

        return array_merge(
            $childOptions,
            array_key_exists($name, $overrideOptions) ? $overrideOptions[$name] : []
        );
    }
}