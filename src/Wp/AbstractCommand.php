<?php

namespace Dhii\WpProvision\Wp;

use UnexpectedValueException;
use Dhii\WpProvision\Command;

/**
 * Description of AbstractCommand.
 *
 * @since [*next-version*]
 */
abstract class AbstractCommand
{
    /** @since [*next-version*] */
    const CMD = '';

    /**
     * @since [*next-version*]
     *
     * @var Command\WpCliCommandInterface
     */
    protected $wpCli;

    /**
     * @since [*next-version*]
     *
     * @var bool
     */
    protected $isOutput;

    /**
     * Parameterless private constructor.
     *
     * @since [*next-version*]
     */
    protected function _construct()
    {
    }

    /**
     * Retrieve the main command that this command represents.
     *
     * @since [*next-version*]
     *
     * @return string The main command.
     */
    protected function _getMainCommand()
    {
        return static::CMD;
    }

    /**
     * Retrieve an array of all parts of a complete command.
     *
     * @since [*next-version*]
     *
     * @param mixed[] $parts The array of additional command parts.
     *
     * @return string[] Parts of the command.
     */
    protected function _getCmdParts($parts = [])
    {
        return array_merge([$this->_getMainCommand()], $parts);
    }

    /**
     * Processes options, cleans and flattens them, groups them with arguments, and adds essential command parts.
     *
     * @since [*next-version*]
     *
     * @param string[] $args    Arguments to the main command.
     * @param mixed[]  $options Additional options to the command.
     *
     * @return string[] An array of command parts that is ready to be run.
     */
    protected function _prepareCmdParts($args = [], $options = [])
    {
        $options = $this->_processOptions($options);
        $options = $this->_flattenOptions($options);

        $parts = array_merge($args, $options);
        $parts = $this->_removeNull($parts);
        $parts = $this->_getCmdParts($parts);

        return $parts;
    }

    /**
     * Remove all null values from an array.
     *
     * @since [*next-version*]
     *
     * @param mixed[] $array An array of values, some of which may be null.
     *
     * @return mixed[] An array of values, none of which are null.
     */
    protected function _removeNull($array)
    {
        $array = array_filter($array, function ($value) {
            return !is_null($value);
        });

        return $array;
    }

    /**
     * Remove empty values from an array.
     *
     * @since [*next-version*]
     *
     * @param mixed[]  $array
     * @param string   $formatIfNot How to format the value if it is not null.
     *                              This will be processed by a `sprintf()` compatible algorithm.
     *                              Default: just the value itself.
     * @param string[] $otherEmpty  Additional values that will be considered to be empty.
     *                              Comparison is done in strict mode.
     *
     * @return mixed[]
     */
    protected function _removeEmpty($array, $formatIfNot = '%1$s', $otherEmpty = [])
    {
        foreach ($array as $_key => $_value) {
            $array[$_key] = $this->_nullIfEmpty($_value, $formatIfNot, $otherEmpty);
        }

        return $this->_removeNull($array);
    }

    /**
     * Converts the value to `null`, if it is empty.
     *
     * @since [*next-version*]
     *
     * @param mixed   $value       The value to process.
     * @param string  $formatIfNot The format to run the value through if it is not empty and is a string.
     *                             Default: string representation of the unchanged value.
     * @param mixed[] $otherEmpty  Other values that are considered to be empty.
     *
     * @return null|mixed A null value, if the original was "empty". The value unchanged otherwise.
     */
    protected function _nullIfEmpty($value, $formatIfNot = '%1$s', $otherEmpty = [])
    {
        return $this->_isValueEmpty($value, $otherEmpty)
                ? null
                : (is_string($value) ? sprintf($formatIfNot, $value) : $value);
    }

    /**
     * Determines if a value is "empty".
     *
     * @since [*next-version*]
     *
     * @param mixed   $value      The value to check.
     * @param mixed[] $otherEmpty Additional values that are considered to be "empty".
     *
     * @return bool True if the value is "empty"; false otherwise.
     */
    protected function _isValueEmpty($value, $otherEmpty = [])
    {
        return is_null($value)
            || (is_array($value) && !count($value))
            || (is_string($value) && !strlen($value))
            || in_array($value, $otherEmpty, true);
    }

    /**
     * Convert one or more values to a coma-separated list.
     *
     * @since [*next-version*]
     *
     * @param string|string[] $values One or many values.
     * @param string          $format The format to run the value through.
     *                                A `sprintf()` compatible format string.
     *
     * @return string The comma-separated list of values.
     */
    protected function _listValues($values, $format = '%1$s')
    {
        if (!is_array($values)) {
            $values = [$values];
        }

        array_walk($values, function (&$value) use ($format) {
            $value = sprintf($format, $value);
        });

        return implode(',', $values);
    }

    /**
     * Retrieve a value from an array.
     *
     * @since [*next-version*]
     *
     * @param type $array
     * @param type $index
     *
     * @return mixed The value at the given index, or the default value if index not found.
     */
    protected function _getValue($array, $index, $default = null)
    {
        return isset($array[$index])
            ? $array[$index]
            : $default;
    }

    /**
     * Process a set of options to make them ready for use with the command line.
     *
     * @since [*next-version*]
     *
     * @param mixed[] $options Options to process.
     *                         Key is the internal option key, value is option value.
     *
     * @return string[] An array where the options' values have been either converted to their CLI value
     *                  representation, or nullified. The keys are converted to their CLI representation as well.
     */
    protected function _processOptions($options)
    {
        $cliKeys = $this->_normalizeOptionKeys($options);
        array_walk($cliKeys, function (&$value) {
            $value = strlen($value) > 1
                ? "--{$value}"
                : "-{$value}";
        });
        $cliValues = $this->_normalizeOptionValues($options);

        $options = array_combine($cliKeys, $cliValues);

        return $options;
    }

    /**
     * Flattens a possibly non-flat array of CLI options.
     *
     * @since [*next-version*]
     *
     * @param array[]|string[] $options An array of options, some of which may be non flat,
     *                                  such as key-value pairs.
     *
     * @return string[]
     */
    protected function _flattenOptions($options)
    {
        $result = [];
        foreach ($options as $_key => $_option) {
            if (is_array($_option)) {
                $result = array_merge($result, $this->_flattenOptions($_option));
                continue;
            }

            if (is_bool($_option)) {
                if (!$_option) {
                    continue;
                }

                $result[] = $_key;
                continue;
            }

            $result[] = "$_key=$_option";
        }

        return $result;
    }

    /**
     * Normalize values of a set of options.
     *
     * It's possible to use a callback for each option - see {@see _getOptionValueMap()}.
     *
     * @since [*next-version*]
     *
     * @param mixed[] $options A set of options, the values of which to normalize.
     *
     * @return string[] An array where the options' values have been either converted to their CLI value
     *                  representation, or nullified
     */
    protected function _normalizeOptionValues($options)
    {
        $allowedKeys = array_flip(array_keys($this->_getOptionKeyMap()));
        $normalizers = $this->_getOptionValueMap();
        $result      = [];
        foreach ($options as $_key => $_value) {
            // If not allowed, skip
            if (!isset($allowedKeys[$_key])) {
                continue;
            }

            // If no normalization, just assign same value
            if (!isset($normalizers[$_key])) {
                $result[$_key] = $_value;
                continue;
            }
            // Normalizer set
            $_normalizers = $normalizers[$_key];

            // Normalize any amount of normalizers to an array of such
            if (!is_array($_normalizers) && is_callable($_normalizers)) {
                $_normalizers = [$_normalizers];
            }

            // If plain value, assign
            if (!is_array($_normalizers)) {
                $result[$_key] = $_normalizers;
                continue;
            }

            // Loop through normalizers and apply each
            foreach ($_normalizers as $_idx => $_normalizer) {
                if (!is_callable($_normalizer)) {
                    throw new UnexpectedValueException(sprintf('Could not normalize option value: index #%1$s for key "%2$s" is not callable', $_idx, $_key));
                }
                $_value = call_user_func_array($_normalizer, [$_value, $_key]);
            }

            $result[$_key] = $_value;
        }

        return $result;
    }

    /**
     * Gets option keys converted from their internal representation to the CLI form.
     *
     * @since [*next-version*]
     *
     * @param string[] $options A set options, the keys of which to get.
     *
     * @return string[] A set of CLI option keys, converted from internal keys. Order preserved.
     */
    protected function _normalizeOptionKeys($options)
    {
        $map  = $this->_getOptionKeyMap();
        $keys = [];
        foreach ($options as $_key => $_value) {
            if (isset($map[$_key])) {
                $keys[] = $map[$_key];
            }
        }

        return $keys;
    }

    /**
     * Retrieve a map of internal to CLI keys.
     *
     * Keys not found here will be excluded from final CLI result.
     *
     * @since [*next-version*]
     *
     * @return string|string[] A map, where keys a re internal keys, and values are CLI keys.
     */
    protected function _getOptionKeyMap()
    {
        return [];
    }

    /**
     * Retrieve a map of internal keys to normalization functions.
     *
     * Keys that do not exist here will get unmodified values.
     * If the key exists, and the value is callable, the value will be passed to the callable, along with
     * the option key, and the return value will be used instead.
     * If the key exists, and the value is not callable, the mapped value will be used unmodified.
     *
     * @since [*next-version*]
     *
     * @return string|string[] A map, where keys a re internal keys, and values normalization callables.
     */
    protected function _getOptionValueMap()
    {
        return [];
    }

    /**
     * Retrieves the WP command instance.
     *
     * @since [*next-version*]
     *
     * @return Command\WpCliCommandInterface
     */
    protected function _getWpCli()
    {
        return $this->wpCli;
    }

    /**
     * Cratate an executale command.
     *
     * @since [*next-version*]
     *
     * @param callable $callback The callback that the command will execute.
     *
     * @throws UnexpectedValueException If the callback does not look like a callable.
     *
     * @return callable An executable command that will run with the specified parameters.
     */
    protected function _createCommand($callback)
    {
        if (!is_callable($callback, true)) {
            throw new UnexpectedValueException('Could not create a command: the callback does not look like a callable');
        }

        return $callback;
    }

    /**
     * Puts quotes around a value.
     *
     * @since [*next-version*]
     *
     * @param string $value The value to quote.
     *
     * @return string The quoted value.
     */
    protected function _quote($value, $isTrueQuote = true)
    {
        return $isTrueQuote
            ? "'{$value}'"
            : sprintf('"%1$s"', $value);
    }

    /**
     * Converts a numeric array to associative based on key position.
     *
     * There can also be string keys. However, the result will contain
     * keys and values in the order determined by the map.
     *
     * ```
     * $input = ['John', 'Doe', 43];
     * $map = ['name', 'surname', 'age'];
     * $result = _normalizeNumAssoc($input, $map);
     * // $result === ['name' => 'John', 'surname' => 'Doe', 'age' => 43]
     * ```
     *
     * @since [*next-version*]
     *
     * @param mixed    $subject An array with numeric or mixed type keys.
     * @param string[] $map     A map of key position to key name.
     */
    protected function _normalizeNumAssoc($subject, $map)
    {
        $values = [];

        foreach ($map as $_index => $_key) {
            if (isset($subject[$_key])) {
                $values[$_key] = $subject[$_key];
            }
            $values[$_key] = isset($subject[$_index])
                ? $subject[$_index]
                : null;
        }

        return $values;
    }

    /**
     * Normalize an option value which represents a complete SSH path.
     *
     * @since [*next-version*]
     *
     * @param mixed  $option The value of an "ssh" type option.
     * @param string $key    The key of the option.
     *
     * @throws UnexpectedValueException if the value is invalid.
     *
     * @return string The complete string representation of the SSH path.
     */
    protected function _normalizeSshOption($option, $key)
    {
        $map    = ['user', 'host', 'port', 'path'];
        $values = $this->_normalizeNumAssoc($option, $map);

        if (!isset($values['host'])) {
            throw new UnexpectedValueException('Could not normalize "ssh" option type: the "host" component must be present');
        }

        $values['user'] = $this->_nullIfEmpty($values['user'], '%1$s@');
        $values['port'] = $this->_nullIfEmpty($values['port'], ':%1$s');

        return implode('', $values);
    }

    /**
     * Normalize an option value which represents a boolean or list value.
     *
     * @since [*next-version*]
     *
     * @param mixed  $option The value of the optipon.
     * @param string $key    The key of the option.
     *
     * @throws UnexpectedValueException if the value is invalid.
     *
     * @return string|null If the option is empty, returns null.
     *                     If it is an array, convert to list.
     *                     Otherwise, true.
     */
    protected function _normalizeBoolOrList($option, $key)
    {
        if (is_null($this->_nullIfEmpty($option))) {
            return false;
        }

        if ($option === true) {
            return $option;
        }

        return $this->_listValues($option);
    }

    /**
     * Normalizes an option value in a way that the option repeats multiple times.
     *
     * Sometimes, it is desirable to repeat an option a few times, and even to hav
     * the occurrences have a value.
     *
     * ```
     * command --require="bootstrap.php" --require="config.php"
     * command --v --v --v
     * ```
     *
     * @since [*next-version*]
     *
     * @param string|strig[] $option A value or multiple values.
     * @param strng          $key    The key of the option.
     *
     * @return type
     */
    protected function _normalizeMultipleUse($option, $key)
    {
        if (is_null($this->_nullIfEmpty($option))) {
            return;
        }

        if (!is_array($option)) {
            $option = [$option];
        }
        $me = $this;
        array_walk($option, function (&$value) use ($me) {
            $value = $me->_isValueEmpty($value, [false])
                ? true
                : sprintf('=%1$s', $me->_quote(trim($value)));
        });

        $result = implode(sprintf(' %1$s=', $key), $option);

        return $result;
    }

    /**
     * Normalize a value to a boolean.
     *
     * @since [*next-version*]
     *
     * @param mixed  $option The option value.
     * @param string $key    The option key.
     *
     * @return bool False if value is empty; true otherwise.
     */
    protected function _normalizeBool($option, $key)
    {
        return $this->_isValueEmpty($option, [false, 0]);
    }

    /**
     * Trims a value.
     *
     * @since [*next-version*]
     *
     * @param mixed  $option The option value.
     * @param string $key    The option key.
     *
     * @return string The trimmed option value.
     */
    protected function _normalizeTrim($option, $key)
    {
        return trim($option);
    }

    /**
     * Write message to log.
     *
     * @since [*next-version*]
     *
     * @param type $message
     *
     * @return \Dhii\WpProvision\Wp\AbstractCommand
     */
    protected function _log($message)
    {
        if ($this->_isOutput()) {
            echo $message;
        }

        return $this;
    }

    /**
     * Get or set whether log entries should be output.
     *
     * @since [*next-version*]
     *
     * @param bool|null $isOutput Whether output is on. If null, will retrieve only.
     *
     * @return bool Whether the output is on. If setting, returns whether it was on before.
     */
    protected function _isOutput($isOutput = null)
    {
        $wasOutput = $this->isOutput;
        if (!is_null($isOutput)) {
            $this->isOutput = (bool) $isOutput;
        }

        return $wasOutput;
    }

    /**
     * Create an instance of the output object.
     *
     * @since [*next-version*]
     *
     * @param string $text The output text.
     *
     * @return Output\OutputInterface The output object instance.
     */
    abstract protected function _createOutput($text);
}
