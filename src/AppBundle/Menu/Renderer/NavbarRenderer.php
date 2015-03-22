<?php

namespace AppBundle\Menu\Renderer;

use Knp\Menu\ItemInterface;
use Knp\Menu\Matcher\MatcherInterface;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class NavbarRenderer
 *
 * @package AppBundle\Menu\Renderer
 */
class NavbarRenderer extends AdvancedRenderer
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
        $defaultOptions = array_merge(['ancestorClass' => 'active', 'currentClass' => 'active', 'depth' => 1, 'dropdown' => false, 'listAttributes' => ['class' => 'nav navbar-nav'], 'translationDomain' => 'menu'], $defaultOptions);

        parent::__construct($matcher, $defaultOptions, $charset, $translator);
    }

    protected function prepareListAttributes(ItemInterface $item, array $attributes, array $options)
    {
        $attributes = parent::prepareListAttributes($item, $attributes, $options);

        // Option: dropdown
        if (array_key_exists('dropdown', $options) && $options['dropdown'] === true && $item->getParent() !== null) {
            $attributes['class'] = ''; // reset list classes
            $attributes = $this->merge($attributes, ['class' => 'dropdown-menu', 'role' => 'menu']);
        }

        return $attributes;
    }

    /**
     * @param ItemInterface $item
     * @param array $attributes
     * @param array $options
     *
     * @return array
     */
    protected function prepareItemAttributes(ItemInterface $item, array $attributes, array $options)
    {
        $attributes = parent::prepareItemAttributes($item, $attributes, $options);

        // Option: dropdown
        if (array_key_exists('dropdown', $options) && $options['dropdown'] === true && $item->hasChildren()) {
            $attributes = $this->merge($attributes, ['class' => 'dropdown']);
        }

        return $attributes;
    }

    /**
     * @param ItemInterface $item
     * @param array $options
     *
     * @return string
     */
    protected function renderLink(ItemInterface $item, array $options = array())
    {
        // Option: dropdown
        if (array_key_exists('dropdown', $options) && $options['dropdown'] === true && $item->hasChildren()) {
            return $this->format($this->renderDropdownElement($item, $options), 'link', $item->getLevel(), $options);
        }

        return parent::renderLink($item, $options);
    }

    /**
     * @param ItemInterface $item
     * @param array $options
     *
     * @return string
     */
    protected function renderDropdownElement(ItemInterface $item, array $options)
    {
        return sprintf('<a href="%s"%s>%s <span class="caret"></span></a>',
            '#', // override URI here so we don't get a route mismatch earlier
            $this->renderHtmlAttributes($this->merge($item->getLinkAttributes(), [
                'class' => 'dropdown-toggle',
                'data-toggle' => 'dropdown',
                'role' => 'button',
                'aria-expanded' => 'false'
            ])),
            $this->renderLabel($item, $options)
        );
    }
} 