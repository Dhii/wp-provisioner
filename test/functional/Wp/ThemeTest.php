<?php

namespace Dhii\WpProvisioner\FuncTest\Wp;

use Dhii\WpProvision\Wp\Theme;
use Dhii\WpProvision\Wp\ThemeInterface;
use Dhii\WpProvision\Process\SymfonyProcessBuilderAdapter;
use Dhii\WpProvision\Env\Bash;

/**
 * Tests {@see Theme}.
 *
 * @since [*next-version*]
 */
class ThemeTest extends \Xpmock\TestCase
{
    /**
     * Creates a new instance of the test subject.
     *
     * @since [*next-version*]
     *
     * @param \Dhii\WpProvision\Command\WpCli $cli The WP CLI object that will be used to run the command.
     *
     * @return Theme The new instance of the test subject.
     */
    public function createInstance($cli)
    {
        $mock = $this->mock('Dhii\\WpProvision\\Wp\\Theme')
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

        $this->assertInstanceOf('Dhii\\WpProvision\\Wp\\ThemeInterface', $subject, 'A valid instance of the test subject could not be created');
    }

    /**
     * Tests whether or not the test subject can retrieve the status of one theme.
     *
     * @since [*next-version*]
     */
    public function testGetStatusSingle()
    {
        $subject = $this->createInstance($this->createWpCli());

        $status = $subject->getStatus('twentysixteen');
        $this->assertEquals([
            ThemeInterface::K_NAME      => 'Twenty Sixteen',
            ThemeInterface::K_STATUS    => ThemeInterface::STATUS_INACTIVE,
            ThemeInterface::K_VERSION   => '1.3',
            ThemeInterface::K_AUTHOR    => 'the WordPress team',
            ThemeInterface::K_SLUG      => 'twentysixteen'
        ], $status, 'Could not correctly determine single theme status');
    }

    /**
     * Tests whether or not the test subject can retrieve the status of multiple themes.
     *
     * @since [*next-version*]
     */
    public function testGetStatusMultiple()
    {
        $subject = $this->createInstance($this->createWpCli());

        $status = $subject->getStatus(null);
        $this->assertContains([
            ThemeInterface::K_SLUG      => 'twentysixteen',
            ThemeInterface::K_STATUS    => ThemeInterface::STATUS_INACTIVE,
            ThemeInterface::K_VERSION   => '1.3'
        ], $status, 'Could not correctly determine multiple theme status');
    }
}