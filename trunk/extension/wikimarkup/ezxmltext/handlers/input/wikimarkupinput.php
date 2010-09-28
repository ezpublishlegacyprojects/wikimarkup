<?php
/**
 * $Id$
 * $HeadURL$
 *
 */

class wikiMarkupInput extends eZSimplifiedXMLInput
{

    function __construct( &$xmlData, $aliasedType, $contentObjectAttribute )
    {
        parent::__construct( $xmlData, $aliasedType, $contentObjectAttribute );
    }
    
    function validateInput( $http, $base, $contentObjectAttribute )
    {
        if ( $http->hasPostVariable( $base . '_data_text_' . $contentObjectAttribute->attribute( 'id' ) ) )
        {
            $postVar = $base . '_data_text_' . $contentObjectAttribute->attribute( 'id' );
            $text = $http->postVariable( $postVar );
            eZDebug::writeDebug( $text, '_POST[' . $postVar . ']' ); 

            $parser = new wikiMarkupInputParser();
            $document = $parser->process( $text );

            if ( $contentObjectAttribute->validateIsRequired() )
            {
                $root = $document->documentElement;
                if ( $root->childNodes->length == 0 )
                {
                    $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                                         'Content required' ) );
                    return eZInputValidator::STATE_INVALID;
                }
            }

            // TODO manage relations

            $xmlString = eZXMLTextType::domString( $document );
            eZDebug::writeDebug( $xmlString, 'Result of wikiMarkupInputParser()->process()' );
            $contentObjectAttribute->setAttribute( 'data_text', $xmlString );

            return eZInputValidator::STATE_ACCEPTED;
        }
        else
        {
            return eZInputValidator::STATE_ACCEPTED;
        }
    }



    function customObjectAttributeHTTPAction( $http, $action, $contentObjectAttribute )
    {
    }


    function editTemplateSuffix( &$contentObjectAttribute )
    {
        return 'wikimarkup';
    }

    function inputXML()
    {
        eZDebug::writeDebug( $this->XMLData );
        $ezdoc = new ezcDocumentEzXml();
        $ezdoc->loadString( $this->XMLData );

        $converter = new ezcDocumentEzXmlToDocbookConverter();
        $converter->options->linkProvider = new wikiMarkupLinkProvider();
        $docbook = $converter->convert( $ezdoc );

        $wiki = new ezcDocumentWiki();
        $wiki->createFromDocbook( $docbook );
        return $wiki->save();
    }
}

