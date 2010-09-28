<?php
/*
 * $Id$
 * $HeadURL$
 *
 */

class wikiMarkupLinkProvider extends ezcDocumentEzXmlLinkProvider
{
    const DEFAULT_URL = '/';

    public function fetchUrlById( $id, $view, $showPath )
    {
        $urlObject = eZURL::fetch( $id );
        if ( $urlObject instanceof eZURL )
        {
            return $urlObject->attribute( 'url' );
        }
        return self::DEFAULT_URL;
    }

    public function fetchUrlByNodeId( $id, $view, $showPath )
    {
        $url = 'content/view/' . ( $view ? $view : 'full' ) . '/' . $id;
        if ( !$view )
        {
            $node = eZContentObjectTreeNode::fetch( $id );
            if ( $node instanceof eZContentObjectTreeNode )
            {
                $url = $node->attribute( 'url_alias' );
            }
            else
            {
                $url = self::DEFAULT_URL;
            }
        }
        return $url;
    }

    public function fetchUrlByObjectId( $id, $view, $showPath )
    {
        $object = eZContentObject::fetch( $id );
        if ( $object instanceof eZContentObject )
        {
            return $this->fetchUrlByNodeId( $object->attribute( 'main_node_id' ), $view, $showPath );
        }
        return self::DEFAULT_URL;
    }

}


?>
