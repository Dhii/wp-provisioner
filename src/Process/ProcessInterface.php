<?php
# -*- coding: utf-8 -*-

namespace WpProvision\Process;

/**
 * Interface Process.
 *
 * Incomplete interface for Symfony\Component\Process\Process
 *
 * @see Symfony\Component\Process\Process
 */
interface ProcessInterface
{
    /**
     * @param int|float|null $timeout The timeout in seconds
     *
     * @see Symfony\Component\Process\Process::setTimeout()
     *
     * @throws \InvalidArgumentException if the timeout is negative
     *
     * @return self
     */
    public function setTimeout($timeout);

    /**
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
     * @param callable|null $callback
     *
     * @see Symfony\Component\Process\Process::mustRun()
     *
     * @return self
     */
    public function mustRun(callable $callback = null);

    /**
     * @see Symfony\Component\Process\Process::getOutput()
     *
     * @throws \LogicException in case the output has been disabled or the process is not started
     *
     * @return string
     */
    public function getOutput();
}
