<?php

namespace AppBundle\Menu\Renderer;

use Knp\Menu\ItemInterface;
use Knp\Menu\Matcher\MatcherInterface;
use Knp\Menu\Renderer\ListRenderer;

class NavbarRenderer extends AdvancedRenderer
{
    public function __construct(MatcherInterface $matcher, array $defaultOptions = [], $charset = null)
    {
        // Manipulate default options
        $defaultOptions = array_merge([
            'ancestorClass' => 'active',
            'currentClass' => 'active',
            'depth' => 1,
            'listAttributes' => ['class' => 'nav navbar-nav']
        ], $defaultOptions);

        parent::__construct($matcher, $defaultOptions, $charset);
    }
} 