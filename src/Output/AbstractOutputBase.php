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
     * @param string|mixed $text The output.
     */
    public function __construct($text)
    {
        $this->_setText($text);
        $info = $this->_getParsedStructure($text);
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

    /**
     * Parses output into a structure.
     *
     * Falls back onto parsing a status message if the first attempt throws.
     * If status message parser throws as well, re-throws the first exception,
     * preserving the cause.
     *
     * @since [*next-version*]
     *
     * @param string $output
     *
     * @throws ParsingException If the output cannot be parsed.
     *
     * @return mixed[] An array that will contain the following indices:
     *                 K_HEADER, K_BODY, K_FOOTER, K_DATA
     */
    protected function _getParsedStructure($output)
    {
        try {
            return $this->_parse($output);
        } catch (ParsingException $e1) {
            try {
                $output = $this->_createFallbackParser($output);
            } catch (\Exception $e2) {
                // Throw first exception with added link to new exception
                throw new ParsingException($e1->getMessage(), $e1->getCode(), $e2);
            }

            $info = [
                self::K_HEADER => $output->getMessage(),
                self::K_BODY   => $output->getText(),
                self::K_FOOTER => null,
                self::K_DATA   => $output,
            ];

            return $info;
        }
    }

    protected function _createFallbackParser($output)
    {
        return new StatusMessage($output);
    }
}
