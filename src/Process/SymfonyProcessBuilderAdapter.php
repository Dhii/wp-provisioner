<?php
# -*- coding: utf-8 -*-

namespace Dhii\WpProvision\Process;

use Symfony\Component\Process\ProcessBuilder as SymfonyProcessBuilder;

/**
 * Class SymfonyProcessBuilderAdapter.
 *
 * Adapts the Process\ProcessBuilder interface with the
 * Symfony ProcessBuilder implementation
 *
 * @since [*next-version*]
 */
class SymfonyProcessBuilderAdapter
    extends SymfonyProcessBuilder
    implements ProcessBuilderInterface
{
}
