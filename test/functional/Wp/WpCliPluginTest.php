<?php

namespace Dhii\WpProvisioner\FuncTest\Wp;

use Dhii\WpProvision\Wp\WpCliPlugin;
use Dhii\WpProvision\Process\SymfonyProcessBuilderAdapter;
use Dhii\WpProvision\Env\Bash;

/**
 * Tests {@see PluginInterface}.
 *
 * @since [*next-version*]
 */
class WpCliPluginTest extends \Xpmock\TestCase
{
    /**
     * Creates a new instance of the test subject.
     *
     * @since [*next-version*]
     *
     * @param \Dhii\WpProvision\Command\WpCli $cli The WP CLI object that will be used to run the command.
     *
     * @return WpCliPlugin The new instance of the test subject.
     */
    public function createInstance($cli)
    {
        $mock = $this->mock('Dhii\\WpProvision\\Wp\\WpCliPlugin')
            ->new($cli);

        return $mock;
    }

    /**
     * Creates a new instance of the WP CLI command.
     *
     * @since [*next-version*]
     *
     * @return \Dhii\WpProvision\Command\WpCli;
     */
    public function createWpCli()
    {
        $binPath = WP_CLI_ROOT . '/bin/wp';
        $processBuilder = $this->createProcessBuilder();

        return new \Dhii\WpProvision\Command\WpCli($this->createShell($processBuilder), $binPath, $processBuilder);
    }

    /**
     * Creates a new instance of a CLI shell.
     *
     * @since [*next-version*]
     *
     * @param SymfonyProcessBuilderAdapter $builder A process builder instance.
     *
     * @return Bash The new shell.
     */
    public function createShell(SymfonyProcessBuilderAdapter $builder)
    {
        return new Bash($builder);
    }

    /**
     * Creates a new instance of a process builder.
     *
     * @since [*next-version*]
     *
     * @return SymfonyProcessBuilderAdapter
     */
    public function createProcessBuilder()
    {
        $builder = new SymfonyProcessBuilderAdapter();
        $builder->setWorkingDirectory(WP_CLI_ROOT . '/../../../web');

        return $builder;
    }

    /**
     * Tests whether a valid instance of the test subject can be created.
     *
     * @since [*next-version*]
     */
    public function testCanBeCreated()
    {
        $subject = $this->createInstance($this->createWpCli());

        $this->assertInstanceOf('Dhii\\WpProvision\\Wp\\PluginInterface', $subject, 'A valid instance of the test subject could not be created');
    }

    /**
     * Tests whether the `isInstalled()` method is working correctly.
     *
     * @since [*next-version*]
     */
    public function testIsActive()
    {
        $subject = $this->createInstance($this->createWpCli());

        $this->assertFalse($subject->isActive('non-exiting-plugin'), 'Could not correctly determine that a plugin is not active');
        $this->assertTrue($subject->isActive('wp-cli-site-url'), 'Could not correctly determine that a plugin is active');
    }
}