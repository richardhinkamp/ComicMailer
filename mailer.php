<?php
/**
 * Mailer script
 *
 * @package ComicMailer
 * @version $Id$
 * @author Richard Hinkamp <richard@hinkamp.nl>
 * @copyright Copyright 2011 Richard Hinkamp
 */

require_once( __DIR__ . '/bootstrap.php' );

require_once( __DIR__.'/vendor/Swift/swift_required.php' );

use ComicMailer\Comic\Collection;

$col = new Collection();
$col->loadYaml( __DIR__ . '/comics.yaml' );
$col->fetchAll();
$new = $col->getAllNew();
if ( $new->count() > 0 )
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