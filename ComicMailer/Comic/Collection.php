<?php
/**
 * Collection of comics
 *
 * @package ComicMailer
 * @author Richard Hinkamp <richard@hinkamp.nl>
 * @copyright Copyright 2011-2012 Richard Hinkamp
 */

namespace ComicMailer\Comic;

/**
 * Collection of comics
 */
class Collection
{
    /**
     * @var \ArrayObject
     */
    protected $comics;

    public function __construct()
    {
        $this->comics = new \ArrayObject();
    }

    /**
     * Load comics from YAML file
     *
     * @param string $file
     */
    public function loadYaml( $file )
    {
        $yaml = new \Symfony\Component\Yaml\Parser();
        $value = $yaml->parse( file_get_contents( $file ) );
        foreach ( $value as $id => $params ) {
            $this->comics->append( new Entry( $id, $params ) );
        }
    }

    /**
     * Save current comics to a YAML file
     *
     * @param string $file
     */
    public function saveYaml( $file )
    {
        $dumper = new \Symfony\Component\Yaml\Dumper();
        $array = array();
        foreach ( $this->comics as $c ) {
            /** @var $c Entry */
            $array[$c->getId()] = $c->getYamlArray();
        }
        $yaml = $dumper->dump( $array, 2 );
        file_put_contents( $file, $yaml );
    }

    /**
     * Fetch all
     */
    public function fetchAll()
    {
        foreach ( $this->comics as $c ) {
            /** @var $c Entry */
            $c->fetch();
        }
    }

    /**
     * Get all new comics
     *
     * @return \ArrayObject
     */
    public function getAllNew()
    {
        $res = new \ArrayObject();
        foreach ( $this->comics as $c ) {
            /** @var $c Entry */
            if ($c->isNew()) {
                $res->append( $c );
            }
        }
        return $res;
    }
}
