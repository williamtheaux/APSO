<?php
/**
 * @version 0.6.1
 * @license MIT license
 * @link    https://chagry.com
 * @author  Grigori <git@chagry.com>
 * @package index.php
 */

// Define secu var chag.
define('CHAG', 1);
define('PATH', dirname(__FILE__));
define('SL', DIRECTORY_SEPARATOR);

// Error reporting: add comment from testing Environment.
error_reporting(0);

// Import lib load.
require_once PATH.SL.'lib'.SL.'load.php';

// Start load.
load::start();

// class Params start().
params::start();

// Start api.
app::start();

?>