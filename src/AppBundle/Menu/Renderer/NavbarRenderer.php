<?php

namespace AppBundle\Menu\Renderer;

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
        $defaultOptions = array_merge(['ancestorClass' => 'active', 'currentClass' => 'active', 'depth' => 1, 'listAttributes' => ['class' => 'nav navbar-nav'], 'translationDomain' => 'menu'], $defaultOptions);

        parent::__construct($matcher, $defaultOptions, $charset, $translator);
    }
} 