<?php

namespace AppBundle\Menu\Render;

use Knp\Menu\ItemInterface;
use Knp\Menu\Matcher\MatcherInterface;
use Knp\Menu\Renderer\ListRenderer;

class SidebarRenderer extends AdvancedRenderer
{
    public function __construct(MatcherInterface $matcher, array $defaultOptions = [], $charset = null)
    {
        // Manipulate default options
        $defaultOptions = array_merge([
            'depth' => 1,
            'currentClass' => 'active',
            'listAttributes' => ['class' => 'list-group'],
            'listElement' => 'div',
            'itemAttributes' => ['class' => 'list-group-item'],
            'itemElement' => false
        ], $defaultOptions);

        parent::__construct($matcher, $defaultOptions, $charset);
    }

    public function render(ItemInterface $item, array $options = array())
    {
        // Manipulate items
        $order = ['Close'];
        $order = array_merge($order, array_keys($item->getChildren()));

        $item->addChild('Close', ['uri' => '#'])->setAttributes([
            'class' => 'visible-xs',
            'data-toggle' => 'offcanvas'
        ]);

        $item->reorderChildren($order);

        return parent::render($item, $options);
    }
} 