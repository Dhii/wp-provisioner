<?php

namespace Dhii\WpProvision\Api;

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
     * @param string  $status  Status of the command. One of the {@see CommandResultInterface}::MSG_STATUS_* constants.
     * @param string  $message Status message.
     * @param mixed[] $data    Data passed from the command. Usually the result of parsing.
     * @param string  $text    Full output of the command.
     */
    public function __construct($status, $message = null, $data = [], $text = null)
    {
        $this->_setStatus($this->_normalizeWpcliStatus($status));
        $this->_setMessage($message);
        $this->_setData($data);
        $this->_setText($text);

        $this->_construct();
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function getData()
    {
        return $this->_getData();
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function getMessage()
    {
        return $this->_getMessage();
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function getText()
    {
        return $this->_getText();
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function getStatus()
    {
        return $this->_getStatus();
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function isSuccess()
    {
        return $this->_isSuccess();
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
        $origStatus = $status;
        $status     = strtolower(trim($status));

        if (in_array($status, ['success', self::MSG_STATUS_SUCCESS])) {
            $status = self::MSG_STATUS_SUCCESS;
        } elseif (in_array($status, ['warning', self::MSG_STATUS_WARNING])) {
            $status = self::MSG_STATUS_WARNING;
        } elseif (in_array($status, ['error', self::MSG_STATUS_ERROR])) {
            $status = self::MSG_STATUS_ERROR;
        } else {
            $status = self::MSG_STATUS_INFO;
        }

        return $status;
    }
}
