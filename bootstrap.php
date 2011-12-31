<?php
/**
 * Bootstrap file
 *
 * @package ComicMailer
 * @version $Id$
 * @author Richard Hinkamp <richard@hinkamp.nl>
 * @copyright Copyright 2011 Richard Hinkamp
 */

// timezone, default always amsterdam
if ( function_exists( 'date_default_timezone_set' ) )
{
    date_default_timezone_set( 'Europe/Amsterdam' );
}

require_once( __DIR__ . '/vendor/Symfony/Component/ClassLoader/UniversalClassLoader.php' );

use Symfony\Component\ClassLoader\UniversalClassLoader;

$loader = new UniversalClassLoader();
$loader->registerNamespaces(array(
    'ComicMail' => __DIR__,
    //'Doctrine' => __DIR__.'/vendor',
    'Symfony' => __DIR__.'/vendor',
    //'Silex' => __DIR__.'/vendor',
    //'Twig' => __DIR__.'/vendor',
));
//$loader->registerPrefixes(array(
//    'Pimple' => __DIR__.'/vendor/Pimple',
//));
$loader->register();