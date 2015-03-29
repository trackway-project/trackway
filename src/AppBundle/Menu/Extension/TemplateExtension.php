<?php

namespace AppBundle\Menu\Extension;

use Knp\Menu\Factory\ExtensionInterface;
use Knp\Menu\ItemInterface;

/**
 * Class TemplateExtension
 *
 * @package AppBundle\Menu\Extension
 */
class TemplateExtension implements ExtensionInterface
{
    /**
     * Builds the full option array used to configure the item.
     *
     * @param array $options The options processed by the previous extensions
     *
     * @return array
     */
    public function buildOptions(array $options = [])
    {
        if (!empty($options['listTemplate'])) {
            $options['extras']['listTemplate'] = $options['listTemplate'];
        }
        if (!empty($options['itemTemplate'])) {
            $options['extras']['itemTemplate'] = $options['itemTemplate'];
        }
        if (!empty($options['listClass'])) {
            $options['extras']['listClass'] = $options['listClass'];
        }
        if (!empty($options['itemClass'])) {
            $options['extras']['itemClass'] = $options['itemClass'];
        }

        return $options;
    }

    /**
     * Configures the item with the passed options
     *
     * @param ItemInterface $item
     * @param array $options
     */
    public function buildItem(ItemInterface $item, array $options)
    {
    }
}
