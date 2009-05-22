<?php

include_once( 'kernel/common/template.php' );

$Module = $Params['Module'];
$http = eZHTTPTool::instance();
$tpl = templateInit();

$parameters = array( 'nodes' => array(),
                     'count' => 100,
                     'class' => 2 ); // 2 = article

if ( $http->hasPostVariable( 'Parameters' ) )
{
    $parameters = $http->postVariable( 'Parameters' );
}

if ( $http->hasPostVariable( 'ParametersSerialized' ) )
{
    $parameters = unserialize( $http->postVariable( 'ParametersSerialized' ) );
}

if ( !isset( $parameters['nodes'] ) )
{
    $parameters['nodes'] = array();
}


if ( $http->hasPostVariable( 'GenerateButton' ) )
{
    $parameters = eZLoremIpsum::createObjects( $parameters );

    if ( $parameters['created_count'] < $parameters['total_count'] )
    {
        $tpl->setVariable( 'parameters_serialized', serialize( $parameters ) );
        $parameters['time'] = time();
        $tpl->setVariable( 'parameters', $parameters );

        $Result['content'] = $tpl->fetch( 'design:loremipsum/progress.tpl' );
        $Result['pagelayout'] = 'loremipsum/progress_pagelayout.tpl';
        return;
    }
}

if ( $http->hasPostVariable( 'AddNodeButton' ) )
{
    $classes = eZPersistentObject::fetchObjectList( eZContentClass::definition(),
                                                    array( 'identifier' ), // field filters
                                                    array( 'is_container' => 1 ), // conds
                                                    null, // sort
                                                    null, // limit
                                                    false ); // as object
    $allowedClasses = array();
    foreach ( $classes as $class )
    {
        $allowedClasses[] = $class['identifier'];
    }

    return eZContentBrowse::browse( array( 'action_name' => 'LoremIpsumAddNode',
                                           'from_page' => '/lorem/ipsum',
                                           'class_array' => $allowedClasses,
                                           'persistent_data' => array( 'ParametersSerialized' => serialize( $parameters ) ) ), $Module );
}

if ( $http->hasPostVariable( 'SelectedNodeIDArray' ) &&
     !$http->hasPostVariable( 'BrowseCancelButton' ) )
{
    $parameters['nodes'] = array_unique( array_merge( $parameters['nodes'], $http->postVariable( 'SelectedNodeIDArray' ) ) );
}

if ( $http->hasPostVariable( 'DeleteNodesButton' ) &&
     $http->hasPostVariable( 'DeleteNodeIDArray' ) )
{
    $parameters['nodes'] = array_diff( $parameters['nodes'], $http->postVariable( 'DeleteNodeIDArray' ) );
}

$tpl->setVariable( 'parameters', $parameters );

$Result['content'] = $tpl->fetch( 'design:loremipsum/main.tpl' );
$Result['path'] = array( array( 'url' => false,
                                'text' => 'Lorem Ipsum Generator' ) );

?>