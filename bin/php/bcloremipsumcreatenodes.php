#!/usr/bin/env php
<?php
/**
 * File containing a script to create a nodes using lorem ipsum content
 *
 * @copyright Copyright (C) 1999 - 2011 Brookins Consulting. All rights reserved.
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.txt GNU GPL v2 (or later)
 * @version //autogentag//
 * @package bcloremipsum
 * @TODO Add script execution timing and reporting.
 */
 
// Load existing class autoloads
require('autoload.php');

// Set script error reporting options
error_reporting( E_ALL | E_NOTICE );

// Load cli and script environment
$cli = eZCLI::instance();
$script = eZScript::instance( array( 'description' => ( "bcloremipsumcreatenodes.php command line script\n" .
                                                         "Creates nodes in the content tree " .
                                                         "at the specified location(s).\n\n" .
                                                         "extension/bcloremipsum/bin/php/bcloremipsumcreatenodes.php --nodes=2,3,4,5 --count=10" ),
                                      'use-session'    => true,
                                      'use-modules'    => true,
                                      'use-extensions' => true ) );

$script->startup();

// Fetch default script options
$options = $script->getOptions( "[nodes:][class-id:][count:][quick:]",
                                "",
                                array( 'nodes'    => 'Comma-separated list of nodes to create nodes under. Required. Example ' . "'--nodes=2,3,4,5,6'. No defaults",
                                       'class-id' => "Id of content class to instantiate objects of. Optional. Example '--class-id=2'. See settings for defaults",
                                       'count'    => "Number of nodes to create. Optional. Example '--count=200'. See settings for defaults",
                                       'quick'    => "Whether to use the quick mode. Optional. Example '--quick=false'. See settings for defaults" ) );

$script->initialize();

// Default creation parameter settings values

// Default parent node ID, root node ID setting
$defaultNodeID = eZINI::instance( 'content.ini' )->variable( 'NodeSettings', 'RootNode' );
$nodeIDsString = $defaultNodeID;

// Default class ID to create setting
$defaultClassID = eZINI::instance( 'bcloremipsum.ini' )->variable( 'BCLoremIpsumSettings', 'DefaultClassID' );
$classID = $defaultClassID;

// Default number of nodes to create setting
$defaultCreateNodeCount = eZINI::instance( 'bcloremipsum.ini' )->variable( 'BCLoremIpsumSettings', 'DefaultCreateNodeCount' );
$count = $defaultCreateNodeCount;

// Default quick mode setting
$defaultCreateQuickMode = eZINI::instance( 'bcloremipsum.ini' )->variable( 'BCLoremIpsumSettings', 'DefaultCreateQuickMode' ) == 'enabled' ? true : false;
$quick = $defaultCreateQuickMode;

// Default trim string setting
$defaultCreateTrimString = eZINI::instance( 'bcloremipsum.ini' )->variable( 'BCLoremIpsumSettings', 'DefaultCreateTrimStrings' ) == 'enabled' ? true : false;
$trim = $defaultCreateTrimString;

// Test for required script parameters, 'nodes'
if ( !empty( $options['nodes'] ) )
{
    $nodes = explode( ',', $options['nodes'] );
    $nodeIDsString = implode( ',', $nodes );
}
else
{
    // Alert user of script requirements
    $cli->warning( "Please specify where to create nodes (Comma-separated list of nodes to create nodes under)." );
    $script->showHelp();
    $script->shutdown( 1 );
}

// Test script parameter defaults
if ( !empty( $options['class-id'] ) )
{
    $classID = $options['class-id'];
}
if ( !empty( $options['count'] ) )
{
    $count = $options['count'];
}
if ( isset( $options['quick'] ) && $options['quick'] == 'false' )
{
    $quick = false;
}

/**
 * Generate node creation parameters
 */
$parameters = array( 'class' => $classID,
                     'nodes' => $nodes,
                     'count' => $count,
                     'quick' => $quick,
                     'trim' => $trim,
                     'cli'   => true,
                     'debug' => false,
                     'verbose' => false );

// Generate attribute parameters
$success = BCLoremIpsum::generateAttributeParameters( $parameters );

// Test for parameter generation success, Terminate script if above fails
if ( !$success )
{
    $script->shutdown( 1, 'Error: Failed generating attribute parameters. Most likely the class-id parameter you specified could not be found. Please check the command line arguments your passed or the default extension settings related.' . "\n" );
}

// Alert user to script startup
$cli->output( 'Script creating ' . $count . ' nodes ' . ( $quick ? 'quickly' : 'normally' ) . '. Using class-id ' . $classID . ' under the following parent nodes : ' . $nodeIDsString . "\n" );

// Create content tree nodes based on parameters
$nodeCreationResults = BCLoremIpsum::createNodes( $parameters );

// Prepare script completion result summary message
if ( $nodeCreationResults['created_count'] > 0 &&  $nodeCreationResults['created_count'] == $count )
{
    $exitMessage = 'Script completed normally. Assume nodes created normally. ' . "\n\n";
}
else
{
    $exitMessage = 'Error: Script did not complete normally. Assume all nodes not created normally. ' . "\n\n";
}

// Append script completion results to message
$exitMessage .= 'Check related content trees for some new nodes.' . "\n\n"
                . 'Nodes created: ' . $nodeCreationResults['created_count'] . "\n"
                . 'Nodes creation time: ' . $nodeCreationResults['used_time'] . ' in seconds.' . "\n"
                . 'Parent nodes to check: ' . $nodeIDsString . "\n";

// Alert user to script completion result summary
$cli->output( $exitMessage );

// Exit script normally
$script->shutdown();

?>
