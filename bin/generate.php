#!/usr/bin/env php
<?php
error_reporting( E_ALL | E_NOTICE );

require_once 'autoload.php';



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
$success = eZLoremIpsum::generateAttributeParameters( $parameters );
if ( !$success )
{
    $script->shutdown( 1, 'Failed generating attribute parameters. Most likely the class you specified could not be found.' );
}

// Create objects.
eZLoremIpsum::createObjects( $parameters );

$script->shutdown();
?>
