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
        return [new AppExtension()];
    }

    /**
     * Test if all service configuration files are loaded
     */
    public function testIsLoadableAndIncludeAllFiles()
    {
        $this->load();

        // forms.xml
        $this->assertContainerBuilderHasService('fos_user.change_password.form.factory');
        $this->assertContainerBuilderHasService('fos_user.group.form.factory');
        $this->assertContainerBuilderHasService('fos_user.profile.form.factory');
        $this->assertContainerBuilderHasService('fos_user.registration.form.factory');
        $this->assertContainerBuilderHasService('fos_user.resetting.form.factory');
        $this->assertContainerBuilderHasService('app.form.type.profile');

        // listeners.xml
        $this->assertContainerBuilderHasService('app.event_listener.routing');

        // menu.xml
        $this->assertContainerBuilderHasService('app.menu.extension.icon');
        $this->assertContainerBuilderHasService('app.menu.renderer.navbar');
        $this->assertContainerBuilderHasService('app.menu.renderer.sidebar');

        // security.xml
        $this->assertContainerBuilderHasService('app.security.authorization.voter.basetimeentry');
        $this->assertContainerBuilderHasService('app.security.authorization.voter.membership');
        $this->assertContainerBuilderHasService('app.security.authorization.voter.project');
        $this->assertContainerBuilderHasService('app.security.authorization.voter.task');
        $this->assertContainerBuilderHasService('app.security.authorization.voter.team');
    }
}
