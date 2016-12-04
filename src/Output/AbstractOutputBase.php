<?php

namespace Dhii\WpProvision\Output;

/**
 * Base functionality for output summaries that expose a common interface.
 *
 * @since [*next-version*]
 */
abstract class AbstractOutputBase extends AbstractOutput implements OutputInterface
{
    /**
     * @since [*next-version*]
     *
     * @param type $text
     */
    public function __construct($text)
    {
        $this->_setText($text);
        $info = $this->_parse($text);
        $this->_setBody($info[self::K_BODY]);
        $this->_setHeader($info[self::K_HEADER]);
        $this->_setFooter($info[self::K_FOOTER]);
        $this->_setData($info[self::K_DATA]);

        $this->_construct();
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function getBody()
    {
        return $this->_getBody();
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function getFooter()
    {
        return $this->_getFooter();
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function getHeader()
    {
        return $this->_getHeader();
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
    public function getData()
    {
        return $this->_getData();
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function __toString()
    {
        return $this->_getText();
    }

    /**
     * Create a new instance of a status message object.
     *
     * @since [*next-version*]
     * 
     * @param type $message
     *
     * @return StatusMessageInterface The new status message instance.
     */
    protected function _createStatusMessage($message)
    {
        return new StatusMessage($message);
    }
}
