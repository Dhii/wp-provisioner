<?php

error_reporting(E_ALL | E_STRICT);

require_once dirname(__FILE__).'/../vendor/autoload.php';

if (!defined('WP_CLI_ROOT')) {
    define('WP_CLI_ROOT', dirname(__FILE__).'/../vendor/wp-cli/wp-cli');
}

include WP_CLI_ROOT.'/php/utils.php';
include WP_CLI_ROOT.'/php/dispatcher.php';
include WP_CLI_ROOT.'/php/class-wp-cli.php';
include WP_CLI_ROOT.'/php/class-wp-cli-command.php';

\WP_CLI\Utils\load_dependencies();
