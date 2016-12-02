<?php
# -*- coding: utf-8 -*-

namespace Dhii\WpProvision\Process;

/**
 * Interface Process.
 *
 * Incomplete interface for Symfony\Component\Process\Process
 *
 * @since [*next-version*]
 *
 * @see Symfony\Component\Process\Process
 */
interface ProcessInterface
{
    /**
     * @since [*next-version*]
     *
     * @see Symfony\Component\Process\Process::setTimeout()
     *
     * @param int|float|null $timeout The timeout in seconds
     *
     * @throws \InvalidArgumentException if the timeout is negative
     *
     * @return self
     */
    public function setTimeout($timeout);

    /**
     * @since [*next-version*]
     *
     * @param int|float|null $timeout The timeout in seconds
     *
     * @see Symfony\Component\Process\Process::setIdleTimeout()
     *
     * @throws \LogicException           if the output is disabled
     * @throws \InvalidArgumentException if the timeout is negative
     *
     * @return self
     */
    public function setIdleTimeout($timeout);

    /**
     * @since [*next-version*]
     *
     * @param callable|null $callback
     *
     * @see Symfony\Component\Process\Process::mustRun()
     *
     * @return self
     */
    public function mustRun(callable $callback = null);

    /**
     * @since [*next-version*]
     *
     * @see Symfony\Component\Process\Process::getOutput()
     *
     * @throws \LogicException in case the output has been disabled or the process is not started
     *
     * @return string
     */
    public function getOutput();
}
