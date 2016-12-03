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
     * Puts WP into a specific state that is needed for testing theme commands.
     *
     * @since [*next-version*]
     */
    public function setUp()
    {
        parent::setUp();
        $wp=  realpath(WP_CLI_ROOT . '/../../../vendor/bin/wp');
        exec($wp . ' theme activate --quiet twentyfifteen 2> /dev/null');
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

        $theme = 'twentysixteen';
        $status = $subject->getStatus($theme);
        $this->assertArrayHasKey($theme, $status, 'Status result set did not contain required key');
        $status = array_pop($status);
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

        $theme = 'twentysixteen';
        $status = $subject->getStatus(null);
        $this->assertArrayHasKey($theme, $status, 'Status result set did not contain required key');
        $this->assertContains([
            ThemeInterface::K_SLUG      => $theme,
            ThemeInterface::K_STATUS    => ThemeInterface::STATUS_INACTIVE,
            ThemeInterface::K_VERSION   => '1.3'
        ], $status, 'Could not correctly determine multiple theme status');
    }

    public function testActivate()
    {
        $subject = $this->createInstance($this->createWpCli());
        $theme = 'twentysixteen';
        $res = $subject->activate($theme);
        var_dump($res);
    }
}