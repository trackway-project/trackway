<?php

namespace AppBundle\Tests\DependencyInjection;

use AppBundle\DependencyInjection\AppExtension;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;

class AppExtensionTest extends AbstractExtensionTestCase
{
    /**
     * Return an array of container extensions you need to be registered for each test (usually just the container
     * extension you are testing.
     *
     * @return ExtensionInterface[]
     */
    protected function getContainerExtensions()
    {
        return array(new AppExtension());
    }

    /**
     * Test if all service configuration files are loaded
     */
    public function testIsLoadableAndIncludeAllFiles()
    {
        $this->load();
        // security.xml
        $this->assertContainerBuilderHasService('security.access.team_voter');
        // forms.xml
        $this->assertContainerBuilderHasService('app.registration.form.type');
        // menu.xml
        $this->assertContainerBuilderHasService('app.menu.navbar_renderer');
        $this->assertContainerBuilderHasService('app.menu.sidebar_renderer');
    }
}
