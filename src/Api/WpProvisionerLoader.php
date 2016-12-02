<?php
# -*- coding: utf-8 -*-

namespace Dhii\WpProvision\Api;

use Dhii\WpProvision\App;
use Dhii\WpProvision\Command;
use Dhii\WpProvision\Env;
use Dhii\WpProvision\Process;
use Symfony\Component\Console as SymfonyConsole;
use LogicException;

/**
 * Class WpProvisionerLoader.
 */
class WpProvisionerLoader implements WpProvisionerInterface
{
    const VERSION = 'dev-master';

    /**
     * @var string
     */
    private $vendor_dir;

    /**
     * @var VersionsInterface
     */
    private $versions;

    /**
     * @var Process\ProcessBuilderInterface
     */
    private $process_builder;

    /**
     * @var Command\WpCli
     */
    private $wp_cli;

    /**
     * @var WpCommandProviderInterface
     */
    private $provider;

    /**
     * @param $base_dir
     */
    public function __construct($base_dir)
    {
        $this->bootstrap($base_dir);

        $wp_bin_path = $this->vendor_dir . '/bin/wp';
        if (!file_exists($this->vendor_dir . '/bin/wp')) {
            throw new LogicException("WP executable not found in composer bin dir: '{$this->vendor_dir}/bin/wp'");
        }
        $this->process_builder = new Process\SymfonyProcessBuilderAdapter();
        $this->wp_cli          = new Command\WpCli(
            new Env\Bash(new Process\SymfonyProcessBuilderAdapter()),
            $wp_bin_path,
            $this->process_builder
        );
        $this->provider = new WpCliCommandProvider($this->wp_cli);
        $this->versions = new IsolatedVersions($this->provider);
        $cwd            = getcwd();

        $provison_file = $cwd . '/provision.php';
        if (!file_exists($provison_file) || !is_readable($provison_file)) {
            throw new LogicException("Provision file not exists or is not readable '{$provison_file}'");
        }

        $app = new SymfonyConsole\Application('WP Provisioner', self::VERSION);
        $app->add(new App\Command\Provision($this->versions));
        $this->load_provision_file($provison_file);

        $app->run();
    }

    /**
     * @return VersionsInterface
     */
    public function versionList()
    {
        return $this->versions;
    }

    /**
     * @param $wp_dir
     */
    public function setWpDir($wp_dir)
    {
        $this->process_builder->setWorkingDirectory(realpath($wp_dir));
    }

    private function load_provision_file($file)
    {
        $api = $this;

        require $file;
    }

    /**
     * Todo: Exclude this.
     *
     * @param string $base_dir Path of the libraries root directory
     */
    private function bootstrap($base_dir)
    {

        // when installed separately as »project«
        $autoload_project = $base_dir . '/vendor/autoload.php';
        // when installed as dependency
        $autoload_library = dirname(// /vendor
            dirname(// /dnaber
                $base_dir            // /wp-provisioner
            )
        ) . '/autoload.php';

        if (file_exists($autoload_project)) {
            $this->vendor_dir = $base_dir . '/vendor';
            require_once $autoload_project;
        } elseif (file_exists($autoload_library)) {
            $this->vendor_dir = dirname(dirname($base_dir));
            require_once $autoload_library;
        } else {
            echo "Composer autoload file not found\n";
            exit(1);
        }
    }
}
