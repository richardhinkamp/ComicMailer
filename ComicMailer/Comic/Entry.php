<?php
/**
 * Comics definition
 *
 * @package ComicMailer
 * @version $Id$
 * @author Richard Hinkamp <richard@hinkamp.nl>
 * @copyright Copyright 2011 Richard Hinkamp
 */

namespace ComicMailer\Comic;

class Entry
{
    protected $id;
    protected $url;
    protected $filter;
    protected $prefix;
    protected $imageUrl;
    protected $lastUrl;

    public function __construct( $id, $params )
    {
        $this->id = $id;
        foreach( $params as $field => $value )
        {
            switch( $field )
            {
                case 'url':
                    $this->url = $value;
                    break;
                case 'filter':
                    $this->filter = $value;
                    break;
                case 'prefix':
                    $this->prefix = $value;
                    break;
                case 'lastUrl':
                    $this->lastUrl = $value;
                    break;
            }
        }
    }

    public function fetch()
    {
        $html = @file_get_contents( $this->url );
        if ( $html )
        {
            $crawler = new \Symfony\Component\DomCrawler\Crawler();
            $crawler->addContent( $html );
            $f = $crawler->filter( $this->filter );
            if ( $f->count() )
            {
                $this->imageUrl = $this->prefix . $f->attr( 'src' );
            }
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function getYamlArray()
    {
        $array = array( 'url' => $this->url, 'filter' => $this->filter );
        if ( $this->prefix )
        {
            $array['prefix'] = $this->prefix;
        }
        if ( $this->lastUrl )
        {
            $array['lastUrl'] = $this->lastUrl;
        }
        if ( $this->imageUrl )
        {
            $array['lastUrl'] = $this->imageUrl;
        }
        return $array;
    }

    public function getImageUrl()
    {
        return $this->imageUrl;
    }

    public function isNew()
    {
        return $this->imageUrl && $this->imageUrl != $this->lastUrl;
    }
}
