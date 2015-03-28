<?php

namespace AppBundle\Tests\DependencyInjection;

use AppBundle\DependencyInjection\AppExtension;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;

/**
 * Class AppExtensionTest
 *
 * @package AppBundle\Tests\DependencyInjection
 */
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
     * Test if all services are loaded
     */
    public function testIsLoadableAndIncludeAllServices()
    {
        $this->load();

        // forms.xml
        $this->assertContainerBuilderHasService('app.form.factory.absence');
        $this->assertContainerBuilderHasService('app.form.factory.invitation');
        $this->assertContainerBuilderHasService('app.form.factory.membership');
        $this->assertContainerBuilderHasService('app.form.factory.project');
        $this->assertContainerBuilderHasService('app.form.factory.task');
        $this->assertContainerBuilderHasService('app.form.factory.team');
        $this->assertContainerBuilderHasService('app.form.factory.time_entry');
        $this->assertContainerBuilderHasService('app.form.factory.change_password');
        $this->assertContainerBuilderHasService('app.form.factory.profile');
        $this->assertContainerBuilderHasService('app.form.factory.user');
        $this->assertContainerBuilderHasService('app.form.factory.registration');
        $this->assertContainerBuilderHasService('app.form.factory.resetting_confirm');
        $this->assertContainerBuilderHasService('app.form.factory.resetting_request');

        $this->assertContainerBuilderHasService('app.form.type.absence');
        $this->assertContainerBuilderHasService('app.form.type.invitation');
        $this->assertContainerBuilderHasService('app.form.type.membership');
        $this->assertContainerBuilderHasService('app.form.type.project');
        $this->assertContainerBuilderHasService('app.form.type.task');
        $this->assertContainerBuilderHasService('app.form.type.team');
        $this->assertContainerBuilderHasService('app.form.type.time_entry');
        $this->assertContainerBuilderHasService('app.form.type.change_password');
        $this->assertContainerBuilderHasService('app.form.type.profile');
        $this->assertContainerBuilderHasService('app.form.type.user');
        $this->assertContainerBuilderHasService('app.form.type.registration');
        $this->assertContainerBuilderHasService('app.form.type.resetting_confirm');
        $this->assertContainerBuilderHasService('app.form.type.resetting_request');

        // listeners.xml
        $this->assertContainerBuilderHasService('app.event_listener.locale');
        $this->assertContainerBuilderHasService('app.event_listener.login');
        $this->assertContainerBuilderHasService('app.event_listener.routing');

        // menu.xml
        $this->assertContainerBuilderHasService('app.menu.builder');
        $this->assertContainerBuilderHasService('app.menu.navbar');
        $this->assertContainerBuilderHasService('app.menu.sidebar');
        $this->assertContainerBuilderHasService('app.menu.extension.icon');
        $this->assertContainerBuilderHasService('app.menu.extension.template');

        // security.xml
        $this->assertContainerBuilderHasService('app.security.authorization.voter.basetimeentry');
        $this->assertContainerBuilderHasService('app.security.authorization.voter.membership');
        $this->assertContainerBuilderHasService('app.security.authorization.voter.project');
        $this->assertContainerBuilderHasService('app.security.authorization.voter.task');
        $this->assertContainerBuilderHasService('app.security.authorization.voter.team');
    }
}
