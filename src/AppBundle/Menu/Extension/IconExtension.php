<?php

namespace AppBundle\Menu\Extension;

use Knp\Menu\Factory\ExtensionInterface;
use Knp\Menu\ItemInterface;

class IconExtension implements ExtensionInterface
{
    /**
     * Builds the full option array used to configure the item.
     *
     * @param array $options The options processed by the previous extensions
     *
     * @return array
     */
    public function buildOptions(array $options = array())
    {
        if (!empty($options['icon'])) {
            $options['extras']['icon'] = $options['icon'];
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