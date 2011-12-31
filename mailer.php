<?php
/**
 * Mailer script
 *
 * @package ComicMailer
 * @version $Id$
 * @author Richard Hinkamp <richard@hinkamp.nl>
 * @copyright Copyright 2011 Richard Hinkamp
 */

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

require_once( __DIR__.'/vendor/Swift/swift_required.php' );

use ComicMailer\Comic\Collection;

$col = new Collection();
$col->loadYaml( __DIR__ . '/comics.yaml' );
$col->fetchAll();
$new = $col->getAllNew();
if ( !empty( $new ) )
{
    $txt = $html = array();
    foreach( $new as $entry )
    {
        /** @var $entry \ComicMailer\Comic\Entry */
        $txt[] = $entry->getId() . ': ' . $entry->getImageUrl();
        $html[] = '<img src="' . $entry->getImageUrl() . '" alt="' . $entry->getId() . '">';
    }

    $message = Swift_Message::newInstance()
      ->setSubject( 'Comics' )
      ->setFrom( array( 'richard@hinkamp.nl' => 'Comic Mailer' ) )
      ->setTo( array( 'richard@hinkamp.nl' => 'Richard Hinkamp' ) )
      ->setBody( implode( "\n", $txt ) )
      ->addPart( implode( "<br><br>\n", $html ), 'text/html' );

    $transport = Swift_SmtpTransport::newInstance( 'smtp.googlemail.com', 465, 'ssl' )
      ->setUsername( 'richardhinkamp.prive@gmail.com' )
      ->setPassword( 'resper93' );

    $mailer = Swift_Mailer::newInstance( $transport );

    if ( $mailer->send( $message ) )
    {
        $col->saveYaml( __DIR__ . '/comics.yaml' );
    }
}