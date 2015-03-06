<?php

namespace AppBundle\Menu\Renderer;

use Knp\Menu\ItemInterface;
use Knp\Menu\Matcher\MatcherInterface;

class SidebarRenderer extends AdvancedRenderer
{
    public function __construct(MatcherInterface $matcher, array $defaultOptions = [], $charset = null)
    {
        // Manipulate default options
        $defaultOptions = array_merge(
            [
                'ancestorClass'  => 'active',
                'currentClass'   => 'active',
                'depth'          => 1,
                'itemAttributes' => ['class' => 'list-group-item'],
                'itemElement'    => false,
                'listAttributes' => ['class' => 'list-group'],
                'listElement'    => 'div'],
            $defaultOptions);

        parent::__construct($matcher, $defaultOptions, $charset);
    }

    public function render(ItemInterface $item, array $options = [])
    {
        // Manipulate items
        $order = ['Close'];
        $order = array_merge($order, array_keys($item->getChildren()));

        $item->addChild('Close', ['uri' => '#'])->setAttributes(
            [
                'class'       => 'visible-xs',
                'data-toggle' => 'offcanvas']);

        $item->reorderChildren($order);

        return parent::render($item, $options);
    }
} 