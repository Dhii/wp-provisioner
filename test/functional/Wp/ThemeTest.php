<?php

namespace Dhii\WpProvisioner\FuncTest\Wp;

use Dhii\WpProvision\Wp\Theme;
use Dhii\WpProvision\Process\SymfonyProcessBuilderAdapter;
use Dhii\WpProvision\Env\Bash;
use Dhii\WpProvision\Model;
use Dhii\WpProvision\Api\StatusAwareInterface;

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
    public function testGetStatusSingleSuccess()
    {
        $subject = $this->createInstance($this->createWpCli());
        $themeClass = 'Dhii\\WpProvision\\Model\\Theme';

        $slug = 'twentysixteen';
        $result = $subject->getStatus($slug);
        $this->assertInstanceOf('Dhii\\WpProvision\\Api\\CommandResultInterface', $result, 'Command did not produce a valid result type');
        $this->assertTrue($result->isSuccess(), 'Status check result success could not be determined correctly');
        $this->assertEquals(StatusAwareInterface::STATUS_INFO, $result->getStatus(), 'Status check result status could not be determined correctly');
        $themeSet = $result->getData();
        $this->assertArrayHasKey($slug, $themeSet, 'Status result set did not contain required theme');
        $this->assertContainsOnlyInstancesOf($themeClass, $themeSet, 'Status result set contained invalid items');
        $this->assertCount(1, $themeSet, 'Status result set contained wrong number of items');
        $theme = $themeSet[$slug];

        $this->assertInstanceOf($themeClass, $theme, 'A valid theme could not be retrieved');
        $this->assertEquals('Twenty Sixteen', $theme->getName(), 'Incorrect theme name determined');
        $this->assertEquals(Model\ThemeInterface::STATUS_INACTIVE, $theme->getStatus(), 'Incorrect theme status determined');
        $this->assertFalse($theme->isActive(), 'Incorrect theme activity determined');
        $this->assertTrue($theme->isInstalled(), 'Incorrect theme presence determined');
        $this->assertEquals('1.3', $theme->getVersion(), 'Incorrect theme version determined');
        $this->assertEquals('the WordPress team', $theme->getAuthor(), 'Incorrect theme author determined');
        $this->assertEquals($slug, $theme->getSlug(), 'Incorrect theme slug determined');
    }

    public function testGetStatusSingleFailure()
    {
        $subject = $this->createInstance($this->createWpCli());

        $slug = 'asdsad';
        $result = $subject->getStatus($slug);
        $this->assertInstanceOf('Dhii\\WpProvision\\Api\\CommandResultInterface', $result, 'Command did not produce a valid result type');
        $this->assertFalse($result->isSuccess(), 'Status check result success could not be determined correctly');
        $this->assertEquals(StatusAwareInterface::STATUS_ERROR, $result->getStatus(), 'Status check result status could not be determined correctly');
        $this->assertFalse($result->isSuccess(), 'Status check result status could not be determined correctly');
        $themeSet = $result->getData();
    }

    /**
     * Tests whether or not the test subject can retrieve the status of multiple themes.
     *
     * @since [*next-version*]
     */
    public function testGetStatusMultiple()
    {
        $themeClass = 'Dhii\\WpProvision\\Model\\Theme';
        $subject = $this->createInstance($this->createWpCli());

        $slug = 'twentysixteen';
        $result = $subject->getStatus();
        $this->assertInstanceOf('Dhii\\WpProvision\\Api\\CommandResultInterface', $result, 'Command did not produce a valid result type');
        $this->assertTrue($result->isSuccess(), 'Status check result success could not be determined correctly');
        $this->assertEquals(StatusAwareInterface::STATUS_INFO, $result->getStatus(), 'Status check result status could not be determined correctly');
        $themeSet = $result->getData();
        $this->assertArrayHasKey($slug, $themeSet, 'Status result set did not contain required theme');
        $this->assertContainsOnlyInstancesOf($themeClass, $themeSet, 'Status result set contained invalid items');
        $this->assertGreaterThan(1, count($themeSet), 'Status result set contained wrong number of items');
        $theme = $themeSet[$slug];

        $this->assertInstanceOf($themeClass, $theme, 'A valid theme could not be retrieved');
        $this->assertEquals(Model\ThemeInterface::STATUS_INACTIVE, $theme->getStatus(), 'Incorrect theme status determined');
        $this->assertFalse($theme->isActive(), 'Incorrect theme activity determined');
        $this->assertTrue($theme->isInstalled(), 'Incorrect theme presence determined');
        $this->assertEquals('1.3', $theme->getVersion(), 'Incorrect theme version determined');
        $this->assertEquals($slug, $theme->getSlug(), 'Incorrect theme slug determined');

        $slug = 'twentyfifteen';
        $theme = $themeSet[$slug];
        $this->assertInstanceOf($themeClass, $theme, 'A valid theme could not be retrieved');
        $this->assertEquals(Model\ThemeInterface::STATUS_ACTIVE, $theme->getStatus(), 'Incorrect theme status determined');
        $this->assertTrue($theme->isActive(), 'Incorrect theme activity determined');
        $this->assertTrue($theme->isInstalled(), 'Incorrect theme presence determined');
        $this->assertEquals('1.6', $theme->getVersion(), 'Incorrect theme version determined');
        $this->assertEquals($slug, $theme->getSlug(), 'Incorrect theme slug determined');
    }

    /**
     * Tests whether a theme can be successfully activated with expected results.
     *
     * @since [*next-version*]
     */
    public function testActivate()
    {
        $subject = $this->createInstance($this->createWpCli());
        $theme = 'twentysixteen';
        $result = $subject->activate($theme);
        $this->assertInstanceOf('Dhii\\WpProvision\\Api\\CommandResultInterface', $result, 'Command did not produce a valid result type');
        $this->assertInstanceOf('Dhii\\WpProvision\Output\\StatusMessageInterface', $result->getMessage());
        $this->assertTrue($result->isSuccess(), 'Command was not determined to be successful');
        $this->assertEquals(StatusAwareInterface::STATUS_SUCCESS, $result->getStatus(), 'Command status could not be correctly determines');
    }

    /**
     * Tests what happens if activation fails.
     *
     * @since [*next-version*]
     */
    public function testActivateFailure()
    {
        $subject = $this->createInstance($this->createWpCli());
        $theme = 'non-existing-theme';
        $result = $subject->activate($theme);
        $this->assertInstanceOf('Dhii\\WpProvision\\Api\\CommandResultInterface', $result, 'Command did not produce a valid result type');
        $this->assertFalse($result->isSuccess(), 'Command was not determined to be failed');
        $this->assertEquals(StatusAwareInterface::STATUS_ERROR, $result->getStatus(), 'Command status could not be correctly determined');
    }
}