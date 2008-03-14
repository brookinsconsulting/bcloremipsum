#!/usr/bin/env php
<?php
error_reporting( E_ALL | E_NOTICE );

require_once 'autoload.php';

/**
 * Set object creation parameters to some hardcoded values.
 */
function generateAttributeParameters( &$parameters )
{
    $classID = $parameters['class'];

    $class = eZContentClass::fetch( $classID );
    if ( !is_object( $class ) )
    {
        return false;
    }
    $contentClassAttributeList = $class->fetchAttributes();

    foreach ( $contentClassAttributeList as $classAttr )
    {
        $id = $classAttr->attribute( 'id' );
        $dataType = $classAttr->attribute( 'data_type_string' );
        $attributeParameters = array();

        switch ( $dataType )
        {
            case 'ezstring':
                $attributeParameters['min_words'] = 4;
                $attributeParameters['max_words'] = 6;
                break;

            case 'ezxmltext':
                $attributeParameters['min_pars'] = 4;
                $attributeParameters['max_pars'] = 6;
                $attributeParameters['min_sentences'] = 4;
                $attributeParameters['max_sentences'] = 6;
                break;

            case 'eztext':
                $attributeParameters['min_pars'] = 4;
                $attributeParameters['max_pars'] = 6;
                $attributeParameters['min_sentences'] = 4;
                $attributeParameters['max_sentences'] = 6;
                break;

            case 'ezboolean':
                $attributeParameters['prob'] = 50;
                break;

            case 'ezinteger':
            case 'ezfloat':
            case 'ezprice':
                $attributeParameters['min'] = 0;
                $attributeParameters['max'] = 999;
                break;

            case 'ezuser':
                $attributeParameters = 1;
                break;

            default:
                if ( $classAttr->attribute( 'is_required' ) )
                    eZDebug::writeWarning( "Unsupported attribute datatype: '$dataType'" );
                break;
        }

        if ( $attributeParameters )
            $parameters['attributes'][$id] = $attributeParameters;
    }

    return true;
}

// Default option values
$classID = 2; // Article
$count = 100;
$quick = 1;

// Process command-line options.
$cli = eZCLI::instance();
$script = eZScript::instance( array( 'description' => ( "Lorem Ipsum script\n" .
                                                         "Creating a bunch of objects " .
                                                         "at the specified location(s).\n" .
                                                         "\n" .
                                                         "ipsum-cl.php" ),
                                      'use-session'    => false,
                                      'use-modules'    => true,
                                      'use-extensions' => false ) );

$script->startup();

$options = $script->getOptions( "[nodes:][class-id:][count:][quick:]",
                                "",
                                array( 'nodes'    => 'Comma-separated list of nodes to create objects under.',
                                       'class-id' => "Id of content class to instantiate objects of. [$classID]",
                                       'count'    => "Number of objects to create [$count]",
                                       'quick'    => "Whether to use the quick mode [$quick]"  ) );

$script->initialize();

if ( $options['nodes'] !== null )
    $nodes = explode( ',', $options['nodes'] );
else
{
    $cli->warning( "Please specify where to create objects (node list)." );
    $script->showHelp();
    $script->shutdown( 1 );
}
if ( $options['class-id'] !== null )
    $classID = $options['class-id'];
if ( $options['count'] !== null )
    $count = $options['count'];
if ( $options['quick'] !== null )
    $quick = $options['quick'];

// Generate object creation parameters.
$parameters = array( 'class' => $classID,
                     'nodes' => $nodes,
                     'count' => $count,
                     'quick' => $quick,
                     'cli'   => $cli );
$success = generateAttributeParameters( $parameters );
if ( !$success )
{
    $script->shutdown( 1, 'Failed generating attribute parameters. Most likely the class you specified could not be found.' );
}

// Create objects.
include_once( 'extension/loremipsum/classes/ezloremipsum.php' );
eZLoremIpsum::createObjects( $parameters );

$script->shutdown();
?>
