<?php

namespace Dhii\WpProvision\Output;

use RuntimeException;

/**
 * Represents a WP CLI status message.
 *
 * @since [*next-version*]
 */
class StatusMessage implements StatusMessageInterface
{
    const K_STATUS = 'status';
    const K_TEXT   = 'text';

    protected $text;
    protected $message;
    protected $status;

    /**
     * @since [*next-version*]
     *
     * @param string $message The status message text.
     */
    public function __construct($message)
    {
        $this->_setText($message);
        $info = $this->_parse($message);
        $this->_setMessage($info[self::K_TEXT]);
        $this->_setStatus($info[self::K_STATUS]);

        $this->_construct();
    }

    /**
     * Parameterless private constructor.
     *
     * @since [*next-version*]
     */
    protected function _construct()
    {
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function __toString()
    {
        return $this->getText();
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Assign the message.
     *
     * @since [*next-version*]
     *
     * @param string|object $message The message.
     *
     * @return StatusMessage This instance.
     */
    protected function _setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Assign the status.
     *
     * @since [*next-version*]
     *
     * @param string|object $status The status.
     *
     * @return StatusMessage This instance.
     */
    protected function _setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Assign the complete message text.
     *
     * @since [*next-version*]
     *
     * @param string|object $text The complete message text.
     *
     * @return StatusMessage This instance.
     */
    protected function _setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Converts a WP CLI status message into a structure.
     *
     * Many times, WP CLI will output a status message after running a command, such as:
     * Success: Theme 'twentysixtee' activated.
     *
     * This method helps determine what happened, based on that message.
     *
     * @since [*next-version*]
     *
     * @param string $message The status message to parse.
     *
     * @throws RuntimeException If message could not be parsed.
     *
     * @return string[] An info array with 2 members:
     *                  K_STATUS and K_TEXT, which contain the status and the text of the status message respectively.
     */
    protected function _parse($message)
    {
        $message = trim($message);
        $parts   = explode(':', $message);
        if (!count($parts)) {
            throw new RuntimeException(sprintf('Could not parse WP CLI status message: %1$s', $message));
        }

        $text   = array_shift($parts);
        $status = null;
        if (count($parts) > 0) {
            $status = $text;
            $text   = array_shift($parts);
        }

        $info = [
            self::K_STATUS => trim($status),
            self::K_TEXT   => trim($text),
        ];

        return $info;
    }
}
