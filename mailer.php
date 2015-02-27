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

use ComicMailer\Comic\Collection;

$col = new Collection();
$col->loadYaml( __DIR__ . '/comics.yaml' );
$col->fetchAll();
$new = $col->getAllNew();
if ($new->count() > 0) {
    $txt = $html = array();
    foreach ( $new as $entry ) {
        /** @var $entry \ComicMailer\Comic\Entry */
        $txt[] = $entry->getId() . ': ' . $entry->getImageUrl();
        $html[] = '<img src="' . $entry->getImageUrl() . '" alt="' . $entry->getId() . '">';
    }

    $yaml = new \Symfony\Component\Yaml\Parser();
    $settings = $yaml->parse( file_get_contents( __DIR__ . '/settings.yaml' ) );

    /** @var $message Swift_Message */
    $message = Swift_Message::newInstance();
    $message->setSubject( 'Comics' )
        ->setFrom( array( $settings['mail']['from']['email'] => $settings['mail']['from']['name'] ) )
        ->setTo( array( $settings['mail']['to']['email'] => $settings['mail']['to']['name'] ) )
        ->setBody( implode( "\n", $txt ) )
        ->addPart( implode( "<br><br>\n", $html ), 'text/html' );

    $transport = Swift_SmtpTransport::newInstance(
        $settings['mail']['smtp']['host'], $settings['mail']['smtp']['port'], $settings['mail']['smtp']['security'] );
    if (isset($settings['mail']['smtp']['username']) && isset($settings['mail']['smtp']['password'])) {
        $transport->setUsername( $settings['mail']['smtp']['username'] );
        $transport->setPassword( $settings['mail']['smtp']['password'] );
    }

    $mailer = Swift_Mailer::newInstance( $transport );

    if ($mailer->send( $message )) {
        $col->saveYaml( __DIR__ . '/comics.yaml' );
    }
}
