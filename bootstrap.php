<?php
/**
 * Bootstrap file
 *
 * @package ComicMailer
 * @author Richard Hinkamp <richard@hinkamp.nl>
 * @copyright Copyright 2011-2012 Richard Hinkamp
 */

// timezone, default always amsterdam
if (function_exists( 'date_default_timezone_set' )) {
    date_default_timezone_set( 'Europe/Amsterdam' );
}

require_once( __DIR__ . '/vendor/autoload.php' );