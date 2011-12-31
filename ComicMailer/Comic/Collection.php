<?php
/**
 * Collection of comics
 *
 * @package ComicMailer
 * @version $Id$
 * @author Richard Hinkamp <richard@hinkamp.nl>
 * @copyright Copyright 2011 Richard Hinkamp
 */

namespace ComicMailer\Comic;

class Collection
{
    protected $comics;

    public function __construct()
    {
        $this->comics = new \ArrayObject();
    }

    public function loadYaml( $file )
    {
        $yaml = new \Symfony\Component\Yaml\Parser();
        $value = $yaml->parse( file_get_contents( $file ) );
        foreach( $value as $id => $params )
        {
            $this->comics->append( new Entry( $id, $params ) );
        }
    }

    public function saveYaml( $file )
    {
        $dumper = new \Symfony\Component\Yaml\Dumper();
        $array = array();
        foreach( $this->comics as $c )
        {
            $array[$c->getId()] = $c->getYamlArray();
        }
        $yaml = $dumper->dump( $array, 2 );
        file_put_contents( $file, $yaml );
    }

    public function fetchAll()
    {
        foreach( $this->comics as $c )
        {
            /** @var $c Entry */
            $c->fetch();
        }
    }

    public function getAllNew()
    {
        $res = new \ArrayObject();
        foreach( $this->comics as $c )
        {
            if ( $c->isNew() )
            {
                $res->append( $c );
            }
        }
        return $res;
    }
}
