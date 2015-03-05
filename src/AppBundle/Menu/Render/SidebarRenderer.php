<?php

namespace AppBundle\Menu\Render;

use Knp\Menu\ItemInterface;
use Knp\Menu\Matcher\MatcherInterface;
use Knp\Menu\Renderer\ListRenderer;

class SidebarRenderer extends ListRenderer
{
    public function __construct(MatcherInterface $matcher, array $defaultOptions = [], $charset = null)
    {
        $defaultOptions = array_merge([
            'depth' => 1,
            'currentClass' => 'active'
        ], $defaultOptions);

        parent::__construct($matcher, $defaultOptions, $charset);
    }

    public function render(ItemInterface $item, array $options = array())
    {
        $order = ['Close'];
        $order = array_merge($order, array_keys($item->getChildren()));

        $item->addChild('Close', ['uri' => '#'])->setAttributes([
            'class' => 'visible-xs',
            'data-toggle' => 'offcanvas'
        ]);

        $item->reorderChildren($order);

        return parent::render($item, $options);
    }

    protected function renderList(ItemInterface $item, array $attributes, array $options)
    {
        if (array_key_exists('class', $attributes) && $attributes['class']) {
            $attributes['class'] .= ' list-group';
        } else {
            $attributes['class'] = 'list-group';
        }

        if (array_key_exists('rootAttributes', $options) && is_array($options['rootAttributes'])) {
            if (array_key_exists('class', $options['rootAttributes']) && $options['rootAttributes']['class']) {
                if (array_key_exists('class', $attributes) && $attributes['class']) {
                    $attributes['class'] .= ' ' . $options['rootAttributes']['class'];
                }
            }
        }

        /**
         * Return an empty string if any of the following are true:
         *   a) The menu has no children eligible to be displayed
         *   b) The depth is 0
         *   c) This menu item has been explicitly set to hide its children
         */
        if (!$item->hasChildren() || 0 === $options['depth'] || !$item->getDisplayChildren()) {
            return '';
        }

        $html = $this->format('<div'.$this->renderHtmlAttributes($attributes).'>', 'div', $item->getLevel(), $options);
        $html .= $this->renderChildren($item, $options);
        $html .= $this->format('</div>', 'div', $item->getLevel(), $options);

        return $html;
    }

    protected function renderItem(ItemInterface $item, array $options)
    {
        // if we don't have access or this item is marked to not be shown
        if (!$item->isDisplayed()) {
            return '';
        }

        // create an array than can be imploded as a class list
        $class = (array) $item->getAttribute('class');

        $class[] = 'list-group-item';

        if ($this->matcher->isCurrent($item)) {
            $class[] = $options['currentClass'];
        } elseif ($this->matcher->isAncestor($item, $options['matchingDepth'])) {
            $class[] = $options['ancestorClass'];
        }

        if ($item->actsLikeFirst()) {
            $class[] = $options['firstClass'];
        }
        if ($item->actsLikeLast()) {
            $class[] = $options['lastClass'];
        }

        if ($item->hasChildren() && $options['depth'] !== 0) {
            if (null !== $options['branch_class'] && $item->getDisplayChildren()) {
                $class[] = $options['branch_class'];
            }
        } elseif (null !== $options['leaf_class']) {
            $class[] = $options['leaf_class'];
        }

        // retrieve the attributes and put the final class string back on it
        $attributes = $item->getAttributes();
        if (!empty($class)) {
            $attributes['class'] = implode(' ', $class);
        }

        // render the text/link without the li tag
        if ($item->getUri() && (!$item->isCurrent() || $options['currentAsLink'])) {
            $attributes = array_merge($item->getLinkAttributes(), $attributes);
            $text = sprintf('<a href="%s"%s>%s</a>', $this->escape($item->getUri()), $this->renderHtmlAttributes($attributes), $this->renderLabel($item, $options));
        } else {
            $attributes = array_merge($item->getLabelAttributes(), $attributes);
            $text = sprintf('<span%s>%s</span>', $this->renderHtmlAttributes($attributes), $this->renderLabel($item, $options));
        }

        $html = $this->format($text, 'link', $item->getLevel(), $options);

        // renders the embedded ul
        $childrenClass = (array) $item->getChildrenAttribute('class');
        $childrenClass[] = 'menu_level_'.$item->getLevel();

        $childrenAttributes = $item->getChildrenAttributes();
        $childrenAttributes['class'] = implode(' ', $childrenClass);

        $html .= $this->renderList($item, $childrenAttributes, $options);

        return $html;
    }

    protected function format($html, $type, $level, array $options)
    {
        if ($options['compressed']) {
            return $html;
        }

        switch ($type) {
            case 'div':
            case 'ul':
            case 'link':
                $spacing = $level * 4;
                break;

            case 'a':
            case 'li':
                $spacing = $level * 4 - 2;
                break;
        }

        return str_repeat(' ', $spacing).$html."\n";
    }
} 