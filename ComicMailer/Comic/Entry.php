<?php
/**
 * Comics definition
 *
 * @package ComicMailer
 * @author Richard Hinkamp <richard@hinkamp.nl>
 * @copyright Copyright 2011-2012 Richard Hinkamp
 */

namespace ComicMailer\Comic;

/**
 * Comics definition
 */
class Entry
{
    /**
     * @var int
     */
    protected $id;
    /**
     * @var string
     */
    protected $url;
    /**
     * @var string
     */
    protected $filter;
    /**
     * @var string
     */
    protected $prefix;
    /**
     * @var string
     */
    protected $imageUrl;
    /**
     * @var string
     */
    protected $lastUrl;

    /**
     * @param int $id
     * @param array $params
     */
    public function __construct( $id, array $params )
    {
        $this->id = $id;
        foreach ( $params as $field => $value ) {
            switch ($field) {
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

    /**
     * Fetch this comic
     */
    public function fetch()
    {
        $html = @file_get_contents( $this->url );
        if ($html) {
            $crawler = new \Symfony\Component\DomCrawler\Crawler();
            $crawler->addContent( $html );
            $f = $crawler->filter( $this->filter );
            if ($f->count()) {
                $this->imageUrl = $this->prefix . $f->attr( 'src' );
            }
        }
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get array for saving in YAML
     *
     * @return array
     */
    public function getYamlArray()
    {
        $array = array( 'url' => $this->url, 'filter' => $this->filter );
        if ($this->prefix) {
            $array['prefix'] = $this->prefix;
        }
        if ($this->lastUrl) {
            $array['lastUrl'] = $this->lastUrl;
        }
        if ($this->imageUrl) {
            $array['lastUrl'] = $this->imageUrl;
        }
        return $array;
    }

    /**
     * @return string
     */
    public function getImageUrl()
    {
        return $this->imageUrl;
    }

    /**
     * @return bool
     */
    public function isNew()
    {
        return $this->imageUrl && $this->imageUrl != $this->lastUrl;
    }
}
