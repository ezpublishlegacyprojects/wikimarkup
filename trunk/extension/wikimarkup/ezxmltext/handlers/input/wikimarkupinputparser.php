<?php
/*
 * $Id$
 * $HeadURL$
 *
 */

class wikiMarkupInputParser
{
    function __construct()
    {

    }

    function process( $text, $createRootNode = true )
    {
        $wiki = new ezcDocumentWiki();
        $wiki->loadString( $text );
        $docbook = $wiki->getAsDocbook();
        
        $converter = new ezcDocumentDocbookToEzXmlConverter();
        $converter->options->linkConverter = new wikiMarkupLinkConverter();
        $ezdoc = $converter->convert( $docbook );

        return $ezdoc->getDomDocument();
    }

}


?>
