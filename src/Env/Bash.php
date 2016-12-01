<?php # -*- coding: utf-8 -*-

namespace WpProvision\Env;

use
	WpProvision\Process;

/**
 * Class Bash
 *
 * @package WpProvision\Env
 */
class Bash implements ShellInterface {

	/**
	 * @var Process\ProcessBuilderInterface
	 */
	private $processBuilder;

	/**
	 * @param Process\ProcessBuilderInterface $processBuilder
	 */
	public function __construct( Process\ProcessBuilderInterface $processBuilder ) {

		$this->processBuilder = $processBuilder;
	}

	/**
	 * @param $command
	 *
	 * @return bool
	 */
	public function commandExists( $command ) {

		$process = $this
			->processBuilder
			->setArguments(
				[
					'hash',
					$command,
					'2>/dev/null || echo "false"'
				]
			)
			->getProcess();

		$output = $process
			->mustRun()
			->getOutput();

		return "false" !== trim( $output );
	}

	/**
	 * Verify if a file exists and is executable
	 *
	 * @param $file
	 *
	 * @return bool
	 */
	public function isExecutable( $file ) {

		return file_exists( $file ) && is_executable( $file );
	}

}
