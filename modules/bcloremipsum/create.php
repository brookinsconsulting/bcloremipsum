<?php
/**
 * File containing the bcloremipsum/create module view.
 *
 * @copyright Copyright (C) 1999 - 2011 Brookins Consulting. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2 (or later)
 * @version //autogentag//
 * @package bcloremipsum
 */

/**
 * Default module parameters
 */
$Module = $Params['Module'];

/**
 * Fetch bcloremipsum extension setting values required by module view
 */
$restrictCacheGenerationByRoleID = eZINI::instance( 'bcloremipsum.ini' )->variable( 'BCLoremIpsumSettings', 'CreateNodesChecksForUserRole' ) == 'enabled' ? true : false;
$restrictCacheGenerationRoleID = is_numeric( eZINI::instance( 'bcloremipsum.ini' )->variable( 'BCLoremIpsumSettings', 'CreateNodesChecksForRoleID' ) ) ? eZINI::instance( 'bcloremipsum.ini' )->variable( 'BCLoremIpsumSettings', 'CreateNodesChecksForRoleID' ) : 2;

/**
 * Default datatypes supported by extension using settings
 */
$defaultDatatypeIdentifiersSupported = eZINI::instance( 'bcloremipsum.ini' )->variable( 'BCLoremIpsumSettings', 'DatatypeIdentifiersSupported' );

$datatypeIdentifiersUnsupported = eZINI::instance( 'bcloremipsum.ini' )->variable( 'BCLoremIpsumSettings', 'DatatypeIdentifiersUnsupported' );
$datatypeIdentifiersDeprecatedUnsupported = eZINI::instance( 'bcloremipsum.ini' )->variable( 'BCLoremIpsumSettings', 'DatatypeIdentifiersDeprecatedUnsupported' );
$datatypeIdentifiersDeprecatedWebshopUnsupported = eZINI::instance( 'bcloremipsum.ini' )->variable( 'BCLoremIpsumSettings', 'DatatypeIdentifiersDeprecatedWebshopUnsupported' );
$datatypeIdentifiersUnsupported = array_merge( $datatypeIdentifiersUnsupported, $datatypeIdentifiersDeprecatedUnsupported );
$datatypeIdentifiersUnsupported = array_merge( $datatypeIdentifiersUnsupported, $datatypeIdentifiersDeprecatedWebshopUnsupported );

/**
 * Default parent node ID, root node ID setting
 */
$defaultNodeID = eZINI::instance( 'content.ini' )->variable( 'NodeSettings', 'RootNode' );

/**
 * Default class ID to create setting
 */
$defaultClassID = eZINI::instance( 'bcloremipsum.ini' )->variable( 'BCLoremIpsumSettings', 'DefaultClassID' );

/**
 * Default number of nodes to create setting
 */
$defaultCreateNodeCount = eZINI::instance( 'bcloremipsum.ini' )->variable( 'BCLoremIpsumSettings', 'DefaultCreateNodeCount' );

/**
 * Default quick mode setting
 */
$defaultCreateQuickMode = eZINI::instance( 'bcloremipsum.ini' )->variable( 'BCLoremIpsumSettings', 'DefaultCreateQuickMode' ) == 'enabled' ? true : false;

/**
 * Default trim string setting
 */
$defaultCreateTrimString = eZINI::instance( 'bcloremipsum.ini' )->variable( 'BCLoremIpsumSettings', 'DefaultCreateTrimStrings' ) == 'enabled' ? true : false;

/**
 * Fetch current user and related attributes
 */
$currentUser = eZUser::currentUser();
$UserID = $currentUser->attribute( 'contentobject_id' );
$UserRoleIDList = $currentUser->attribute( 'role_id_list' );

/**
 * Give the unknown Anonymous users a frienly kernel access denied error
 * also give authenticated users without the required role permissions,
 * provided in the bcupdatecache.ini settings,
 * a frienly kernel access denied error as well.
 * Better safe than sorry in this regard
 */
if( $currentUser->isAnonymous() == true or ( $restrictCacheGenerationByRoleID == true && !in_array( $restrictCacheGenerationRoleID, $UserRoleIDList ) ) )
{
    return $Module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel' );
}

/**
 * Default class instances
 */
$http = eZHTTPTool::instance();
$tpl = eZTemplate::factory();

/**
 * Request parameters
 *   'nodes' => array( 2 ), // Defaults to create nodes as primary content tree node_id of 2
 *   'class' => 16, // Defaults to create nodes as primary content class_id of 16 (Article)
 *   'quick' => true, // Defaults to true
 *   'trim' => true // Defaults to true
 */
$parameters = array( 'nodes' => array( $defaultNodeID ),
                     'count' => $defaultCreateNodeCount,
                     'class' => $defaultClassID,
                     'quick' => $defaultCreateQuickMode,
                     'trim' => $defaultCreateTrimString,
                     'debug' => false,
                     'verbose' => false );

eZSession::set( 'bcloremipsum_create_parameters', $parameters );

/**
 * Test for existance of post variable, 'Parameters'
 */
if ( $http->hasPostVariable( 'Parameters' ) )
{
    $parameters = array_merge( $parameters, $http->postVariable( 'Parameters' ) );
    eZSession::set( 'bcloremipsum_create_parameters', $parameters );
}
else
{
    $parameters = eZSession::get( 'bcloremipsum_create_parameters', $parameters );
}

/**
 * Test for existance of post variable, 'ParametersSerialized'
 */
if ( $http->hasPostVariable( 'ParametersSerialized' ) )
{
    $parameters = unserialize( $http->postVariable( 'ParametersSerialized' ) );
    eZSession::set( 'bcloremipsum_create_parameters', $parameters );
}

/**
 * Prepare parameter defaults before use ...
 * @TODO Remove the following unused code
 */
if ( !isset( $parameters['nodes'] ) )
{
    $parameters['nodes'] = array();
}

/**
 * Validate request to generate content
 */
if ( $http->hasPostVariable( 'CreateButton' ) )
{
    /**
     * Perform generate content requests
     */
    $resultParameters = BCLoremIpsum::createNodes( $parameters );
    $parameters = array_merge( $parameters, $resultParameters );

    /**
     * Calculate content generation results on status page
     */
    if ( $resultParameters['created_count'] < $resultParameters['total_count'] )
    {
        $parameters['time'] = time();
        $parameters['time'] = time();

        $resultParametersMerged = array_merge( $parameters, $resultParameters );
        $tpl->setVariable( 'parameters', $resultParametersMerged );

        eZSession::set( 'bcloremipsum_create_parameters', $resultParametersMerged );
        $tpl->setVariable( 'parameters_serialized', serialize( $resultParametersMerged ) );

        $Result['content'] = $tpl->fetch( 'design:bcloremipsum/progress.tpl' );
        $Result['pagelayout'] = 'bcloremipsum/progress_pagelayout.tpl';
        return;
    }
}

/**
 * Validate browse content tree to select root node to perform content generation requests upon
 */
if ( $http->hasPostVariable( 'AddNodeButton' ) )
{
    /**
     * Fetch array of container classes
     */
    $classes = eZPersistentObject::fetchObjectList( eZContentClass::definition(),
                                                    array( 'identifier' ), // field filters
                                                    array( 'is_container' => 1 ), // conds
                                                    null, // sort
                                                    null, // limit
                                                    false ); // as object

    /**
     * Prepare array of allowed class identifiers based on above fetch results
     */
    $allowedClasses = array();
    foreach ( $classes as $class )
    {
        $allowedClasses[] = $class['identifier'];
    }

    /**
     * Return browse for node selection view limited to allowed classes
     */
    return eZContentBrowse::browse( array( 'action_name' => 'BCLoremIpsumCreateAddNode',
                                           'from_page' => '/bcloremipsum/create',
                                           'class_array' => $allowedClasses,
                                           'persistent_data' => array( 'ParametersSerialized' => serialize( $parameters ) ) ), $Module );
}

/**
 * Test for selected nodes from browse selection request to include in parameters
 */
if ( $http->hasPostVariable( 'SelectedNodeIDArray' ) &&
     !$http->hasPostVariable( 'BrowseCancelButton' ) )
{
    $parameters['nodes'] = array_unique( array_merge( $parameters['nodes'], $http->postVariable( 'SelectedNodeIDArray' ) ) );
    eZSession::set( 'bcloremipsum_create_parameters', $parameters );
}

/**
 * Test for delete nodes request parameters and remove nodes from parameters
 */
if ( $http->hasPostVariable( 'DeleteNodesButton' ) &&
     $http->hasPostVariable( 'DeleteNodeIDArray' ) )
{
    $parameters['nodes'] = array_diff( $parameters['nodes'], $http->postVariable( 'DeleteNodeIDArray' ) );
    eZSession::set( 'bcloremipsum_create_parameters', $parameters );
}

/**
 * Pass module view default template parameters
 */
$tpl->setVariable( 'parameters', $parameters );
$tpl->setVariable( 'datatypes_supported', $defaultDatatypeIdentifiersSupported );

/**
 * Prepare module view content results for display to user
 */
$Result['content'] = $tpl->fetch( 'design:bcloremipsum/create.tpl' );
$Result['navigation_part'] = 'ezbcloremipsumnavigationpart';
$Result['path'] = array( array( 'url' => 'bcloremipsum/create',
                                'text' => 'Create nodes' ) );

?>
