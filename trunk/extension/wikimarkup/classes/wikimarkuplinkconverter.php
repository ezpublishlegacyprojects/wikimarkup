<?php
/*
 * $Id$
 * $HeadURL$
 *
 */

class wikiMarkupLinkConverter extends ezcDocumentEzXmlLinkConverter
{

    /**
     * Returns an array containing attribute(s) to set on a link element
     * in an eZXML document
     * 
     * @todo manage content/view/<view>/node_id URL
     * @param string $url 
     * @access public
     * @return array 
     */
    public function getUrlProperties( $url )
    {
        $urlObject = new ezcUrl( $url );
        if ( $urlObject->scheme === null )
        {
            $nodeID = eZURLAliasML::fetchNodeIDByPath( $url );
            if ( $nodeID !== false )
            {
                return array( 'node_id' => $nodeID );
            }
        }
        $urlID = eZURL::registerURL( $url );
        return array( 'url_id' => $urlID );
    }

}


?>
