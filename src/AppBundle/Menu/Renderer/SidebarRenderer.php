<?php

namespace AppBundle\Menu\Renderer;

use Knp\Menu\Matcher\MatcherInterface;

class SidebarRenderer extends AdvancedRenderer
{
    public function __construct(MatcherInterface $matcher, array $defaultOptions = [], $charset = null)
    {
        // Manipulate default options
        $defaultOptions = array_merge(['ancestorClass' => 'active', 'currentClass' => 'active', 'depth' => 1, 'itemAttributes' => ['class' => 'list-group-item'], 'itemElement' => false, 'listAttributes' => ['class' => 'list-group'], 'listElement' => 'div'], $defaultOptions);

        parent::__construct($matcher, $defaultOptions, $charset);
    }
} 