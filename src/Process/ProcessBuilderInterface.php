<?php
# -*- coding: utf-8 -*-

namespace WpProvision\Process;

/**
 * Interface Process.
 *
 * Interface for Symfony\Components\Process\ProcessBuilder
 *
 * @since [*next-version*]
 *
 * @see Symfony\Component\Process\ProcessBuilder
 */
interface ProcessBuilderInterface
{
    /**
     * @since [*next-version*]
     *
     * @see Symfony\Component\Process\ProcessBuilder::create()
     *
     * @param array $arguments
     *
     * @return ProcessBuilderInterface
     */
    public static function create(array $arguments = array());

    /**
     * @since [*next-version*]
     *
     * @see Symfony\Component\Process\ProcessBuilder::setArguments()
     *
     * @param array $arguments
     *
     * @return self
     */
    public function setArguments(array $arguments);

    /**
     * @since [*next-version*]
     *
     * @see Symfony\Component\Process\ProcessBuilder::getProcess()
     *
     * @throws \LogicException In case no arguments have been provided
     *
     * @return ProcessInterface
     */
    public function getProcess();

    /**
     * Sets the working directory.
     *
     * @since [*next-version*]
     *
     * @param null|string $cwd The working directory
     *
     * @return ProcessBuilderInterface
     */
    public function setWorkingDirectory($cwd);
}
