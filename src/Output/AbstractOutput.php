<?php

namespace Dhii\WpProvision\Output;

/**
 * Common functionality for output summaries.
 *
 * @since [*next-version*]
 */
abstract class AbstractOutput
{
    protected $text;
    protected $header;
    protected $body;
    protected $footer;
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
     * Retrieve the complete output text.
     *
     * @since [*next-version*]
     *
     * @return string The message text.
     */
    protected function _getText()
    {
        return $this->text;
    }

    /**
     * Assign the complete message text.
     *
     * @since [*next-version*]
     *
     * @param string $text The complete message text.
     *
     * @return AbstractOutput This instance.
     */
    protected function _setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Retrieve the output header.
     *
     * @since [*next-version*]
     *
     * @return string The header.
     */
    protected function _getHeader()
    {
        return $this->header;
    }

    /**
     * Assign the header.
     *
     * @since [*next-version*]
     *
     * @param string $header The header.
     *
     * @return AbstractOutput This instance.
     */
    protected function _setHeader($header)
    {
        $this->header = $header;

        return $this;
    }

    /**
     * Retrieve the output footer.
     *
     * @since [*next-version*]
     *
     * @return string The footer.
     */
    protected function _getFooter()
    {
        return $this->footer;
    }

    /**
     * Assign the footer.
     *
     * @since [*next-version*]
     *
     * @param string $footer The footer.
     *
     * @return AbstractOutput This instance.
     */
    protected function _setFooter($footer)
    {
        $this->footer = $footer;

        return $this;
    }

    /**
     * Retrieve the body.
     *
     * @since [*next-version*]
     *
     * @return string The body text.
     */
    protected function _getBody()
    {
        return $this->body;
    }

    /**
     * Assign the body.
     *
     * @since [*next-version*]
     *
     * @param string $body The body.
     *
     * @return AbstractOutput This instance.
     */
    protected function _setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * Retrieve the parsed data.
     *
     * @since [*next-version*]
     *
     * @return mixed|null The data that may have been the result of output parsing.
     */
    protected function _getData()
    {
        return $this->data;
    }

    /**
     * Assign the data.
     *
     * @since [*next-version*]
     *
     * @param string $data The data that is a result of output parsing.
     *
     * @return AbstractOutput This instance.
     */
    protected function _setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Parses the output into a structure.
     *
     * @since [*next-version*]
     *
     * @throws RuntimeException If output could not be parsed.
     *
     * @return mixed[] An array that contains all 3 of the following keys (see {@see OutputInterface):
     *                 K_HEADER, K_FOOTER, K_BODY, K_DATA
     */
    abstract protected function _parse($output);
}
