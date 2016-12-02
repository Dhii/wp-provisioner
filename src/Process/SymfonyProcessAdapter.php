<?php
# -*- coding: utf-8 -*-

namespace Dhii\WpProvision\Process;

use Symfony\Component\Process\Process as SymfonyProcess;

/**
 * Class SymfonyProcessAdapter.
 *
 * Adapts the Process\Process interface with the Symfony Process implementation
 *
 * @since [*next-version*]
 */
class SymfonyProcessAdapter
    extends SymfonyProcess
    implements ProcessInterface
{
}
