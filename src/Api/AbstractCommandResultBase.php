<?php

namespace Dhii\WpProvision\Api;

use Symfony\Component\Process\Process;
use Dhii\WpProvision\Output;

/**
 * Base API functionality of a command result.
 *
 * @since [*next-version*]
 */
abstract class AbstractCommandResultBase extends AbstractCommandResult implements CommandResultInterface
{
    /**
     * @since [*next-version*]
     *
     * @param Process                       $process The process that the command created.
     * @param string|Output\OutputInterface $output  Output of the process.
     * @param string                        $status  Status of the result.
     *                                               One of the {@see StatusAwareInterface}::STATUS_* constants.
     */
    public function __construct($process, $output = null, $status = null)
    {
        $status = is_null($status)
            ? $status
            : $this->_normalizeWpcliStatus($status);

        $this->_setProcess($process);
        $this->_setOutput($output);
        $this->_setStatus($status);

        $this->_construct();
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function getOutput()
    {
        return $this->_getOutput();
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function getStatus()
    {
        return $this->_getStatusFallback();
    }

    /**
     * Retrieve the status, falling back on other data.
     *
     * If the status is not set for this instance, attempts to infer it from the output.
     *
     * @since [*next-version*]
     *
     * @return string|null The status code, if any.
     */
    protected function _getStatusFallback()
    {
        if (!is_null($status = $this->_getStatus())) {
            return $status;
        }

        $status = $this->_getStatusFromOutput();
        $status = $this->_normalizeWpcliStatus($status);

        return $status;
    }

    /**
     * Attempts to infer the status from output.
     *
     * If the output has a status message, gets its status.
     *
     * @since [*next-version*]
     *
     * @return string|null The status code, if any.
     */
    protected function _getStatusFromOutput()
    {
        if (!(($output = $this->getOutput()) instanceof Output\OutputInterface)) {
            return;
        }

        if (!(($data = $output->getData()) instanceof Output\StatusMessageInterface)) {
            return;
        }

        if (is_null($status = $data->getStatus())) {
            return;
        }

        return $status;
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function isSuccess()
    {
        return $this->_isStatusSuccessful($this->_getStatusFallback());
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function getProcess()
    {
        return $this->_getProcess();
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function getData()
    {
        if (!is_null($output = $this->_getOutput())) {
            return $output->getData();
        }

        return;
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function __toString()
    {
        return (string) $this->_getOutput();
    }

    /**
     * Converts a WP CLI command status text into one of the pre-defined values.
     *
     * @since [*next-version*]
     *
     * @param type $status
     *
     * @return type
     */
    public function _normalizeWpcliStatus($status)
    {
        $status = strtolower(trim($status));

        if (in_array($status, ['success', self::STATUS_SUCCESS])) {
            $status = self::STATUS_SUCCESS;
        } elseif (in_array($status, ['warning', self::STATUS_WARNING])) {
            $status = self::STATUS_WARNING;
        } elseif (in_array($status, ['error', self::STATUS_ERROR])) {
            $status = self::STATUS_ERROR;
        } else {
            $status = self::STATUS_INFO;
        }

        return $status;
    }
}
