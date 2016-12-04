<?php

namespace Dhii\WpProvision\Api;

/**
 * Common functionality for command results.
 *
 * @since [*next-version*]
 */
class AbstractCommandResult
{
    protected $status;
    protected $text;
    protected $message;
    protected $data;

    /**
     * Parameterless private constructor.
     *
     * @since [*next-version*]
     */
    protected function _construct()
    {
    }

    /**
     * Retrieve the status code of the result.
     *
     * @since [*next-version*]
     *
     * @return string The status code. One of {@see CommandResultInterface}::MSG_STATUS_* constants.
     */
    protected function _getStatus()
    {
        return $this->status;
    }

    /**
     * Assign status code.
     *
     * @since [*next-version*]
     *
     * @param string $status The status code of the command.
     *
     * @return AbstractCommandResult This instance.
     */
    protected function _setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Retrieve the output of the command.
     *
     * @since [*next-version*]
     *
     * @return string The output of the command.
     */
    protected function _getText()
    {
        return $this->text;
    }

    /**
     * Assign command output.
     *
     * @since [*next-version*]
     *
     * @param string $text The output text.
     *
     * @return AbstractCommandResult This instance.
     */
    protected function _setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Retrieve data generated by a command.
     *
     * @since [*next-version*]
     *
     * @param null|string $index A data key to retrieve data for.
     *                           Leave null to retrieve all data.
     *
     * @return mixed[]|mixed[] The data, or a data member value.
     */
    protected function _getData($index = null)
    {
        if (is_null($index)) {
            return $this->data;
        }

        return isset($this->data[$index])
            ? $this->data[$index]
            : null;
    }

    /**
     * Assign data generated by a command.
     *
     * @since [*next-version*]
     *
     * @param string|mixed[] $key   Key of the data member to set.
     *                              If array, this instance's data will be replaced by this.
     * @param null|mixed     $value The value to give to the data member, if $key not an array.
     *
     * @return AbstractCommandResult This instance.
     */
    protected function _setData($key, $value = null)
    {
        if (!is_string($key)) {
            $this->data = $key;

            return $this;
        }

        $this->data[$key] = $value;

        return $this;
    }

    /**
     * Retrieve the status message of the command.
     *
     * @since [*next-version*]
     *
     * @return string The message text.
     */
    protected function _getMessage()
    {
        return $this->message;
    }

    /**
     * Assign the status message of a command.
     *
     * @since [*next-version*]
     *
     * @param string $message The message text.
     *
     * @return AbstractCommandResult This instance.
     */
    protected function _setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Determine whether the command, of which this is a result, was a success.
     *
     * @since [*next-version*]
     *
     * @return bool True if the command was a success; false otherwise.
     */
    protected function _isSuccess()
    {
        return $this->_getStatus() === CommandResultInterface::MSG_STATUS_SUCCESS;
    }
}
