<?php

namespace AppBundle\Menu\Renderer;

use Knp\Menu\ItemInterface;
use Knp\Menu\Matcher\MatcherInterface;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class BreadcrumbRenderer
 *
 * @package AppBundle\Menu\Renderer
 */
class BreadcrumbRenderer extends AdvancedRenderer
{
    /**
     * @param MatcherInterface $matcher
     * @param array $defaultOptions
     * @param string|null $charset
     * @param TranslatorInterface|null $translator
     */
    public function __construct(MatcherInterface $matcher, array $defaultOptions = [], $charset = null, TranslatorInterface $translator = null)
    {
        // Initialize default options
        $defaultOptions = array_merge(['ancestorClass' => '', 'currentAsLink' => false, 'currentClass' => 'active', 'listAttributes' => ['class' => 'breadcrumb'], 'listElement' => 'ol', 'raw' => false, 'translationDomain' => 'menu'], $defaultOptions);

        parent::__construct($matcher, $defaultOptions, $charset, $translator);
    }

    /**
     * @param ItemInterface $item
     * @param array $attributes
     * @param array $options
     *
     * @return string
     */
    protected function renderList(ItemInterface $item, array $attributes, array $options)
    {
        /**
         * Return an empty string if any of the following are true:
         *   a) The menu has no children eligible to be displayed
         *   b) The depth is 0
         *   c) This menu item has been explicitly set to hide its children
         */
        if (0 === $options['depth'] || !$item->hasChildren() || !$item->getDisplayChildren()) {
            return '';
        }

        // Option: raw
        if (array_key_exists('raw', $options) && is_string($options['raw'])) {
            return $this->renderChildren($item, $options);
        }

        // Option: listAttributes
        if (array_key_exists('listAttributes', $options) && is_array($options['listAttributes'])) {
            $attributes = $this->merge($attributes, $options['listAttributes']);
        }

        // Option: listElement
        $listElement = $this->defaultOptions['listElement'];
        if (array_key_exists('listElement', $options)) {
            $listElement = $options['listElement'];
        }

        // Render list
        $html = '';
        if ($listElement && $item->getLevel() === 0) {
            $html = $this->format('<' . $listElement . $this->renderHtmlAttributes($attributes) . '>', $listElement, $item->getLevel(), $options);
        }

        $html .= $this->renderChildren($item, $options);

        if ($listElement && $item->getLevel() === 0) {
            $html .= $this->format('</' . $listElement . '>', $listElement, $item->getLevel(), $options);
        }

        return $html;
    }

    /**
     * @param ItemInterface $item
     * @param array $options
     *
     * @return string
     */
    protected function renderItem(ItemInterface $item, array $options)
    {
        /**
         * Return an empty string if any of the following are true:
         *   a) We don't have access or this item is marked to not be shown
         *   b) The item does not belong the the active path
         */
        if (!$item->isDisplayed() || (!$this->matcher->isCurrent($item) && !$this->matcher->isAncestor($item, $options['matchingDepth']))) {
            return '';
        }

        // Option: raw
        if (array_key_exists('raw', $options) && is_string($options['raw'])) {
            $html = $this->renderLabel($item, $options) . ($item->hasChildren() ? $options['raw'] : '');
            $html .= $this->renderChildren($item, $options);
            return $html;
        }

        return parent::renderItem($item, $options);
    }
}