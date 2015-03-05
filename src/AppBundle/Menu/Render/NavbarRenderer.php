<?php

namespace AppBundle\Menu\Render;

use Knp\Menu\ItemInterface;
use Knp\Menu\Matcher\MatcherInterface;
use Knp\Menu\Renderer\ListRenderer;

class NavbarRenderer extends ListRenderer
{
    public function __construct(MatcherInterface $matcher, array $defaultOptions = [], $charset = null)
    {
        $defaultOptions = array_merge([
            'depth' => 1,
            'currentClass' => 'active'
        ], $defaultOptions);

        parent::__construct($matcher, $defaultOptions, $charset);
    }

    protected function renderList(ItemInterface $item, array $attributes, array $options)
    {
        if (array_key_exists('class', $attributes) && $attributes['class']) {
            $attributes['class'] .= ' nav navbar-nav';
        } else {
            $attributes['class'] = 'nav navbar-nav';
        }

        if (array_key_exists('rootAttributes', $options) && is_array($options['rootAttributes'])) {
            if (array_key_exists('class', $options['rootAttributes']) && $options['rootAttributes']['class']) {
                if (array_key_exists('class', $attributes) && $attributes['class']) {
                    $attributes['class'] .= ' ' . $options['rootAttributes']['class'];
                }
            }
        }

        return parent::renderList($item, $attributes, $options);
    }
} 