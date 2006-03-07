<?php

include_once( 'kernel/common/template.php' );

$Module =& $Params['Module'];
$http =& eZHTTPTool::instance();
$tpl =& templateInit();

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
    include_once( 'kernel/classes/ezcontentclassattribute.php' );
    include_once( 'kernel/classes/ezcontentclass.php' );
    include_once( 'kernel/classes/ezcontentobject.php' );
    include_once( 'kernel/classes/ezcontentobjectversion.php' );
    include_once( 'lib/ezutils/classes/ezoperationhandler.php' );
    include_once( 'extension/loremipsum/classes/ezloremipsum.php' );

    if ( !isset( $parameters['structure'] ) )
    {
        $parameters['structure'] = array();
        $totalCount = 0;
        $count = $parameters['count'];

        foreach ( $parameters['nodes'] as $nodeID )
        {
            $nodeID = ( int ) $nodeID;
            if ( $nodeID )
            {
                $parameters['structure'][$nodeID] = $count;
                $totalCount += $count;
            }
        }

        $parameters['total_count'] = $totalCount;
        $parameters['created_count'] = 0;
        $parameters['start_time'] = time();
    }

    $classID = $parameters['class'];
    
    if ( !$class = eZContentClass::fetch( $classID ) )
    {
        // TODO
        return;
    }
    
    if ( !$attributes =& eZContentClassAttribute::fetchListByClassID( $classID, EZ_CLASS_VERSION_STATUS_DEFINED, false ) )
    {
        // TODO
        return;
    }

    foreach ( $attributes as $attribute )
    {
        if ( $attribute['is_required'] && !isset( $parameters['attributes'][$attribute['id']] ) )
        {
            // TODO
            return;
        }
    }

    $db =& eZDB::instance();
    $db->setIsSQLOutputEnabled( false );

    $startTime = time();
    foreach ( array_keys( $parameters['structure'] ) as $nodeID )
    {
        $node = eZContentObjectTreeNode::fetch( $nodeID );
        if ( !$node )
        {
            // TODO
            continue;
        }
        if ( isset( $parameters['quick'] ) && $parameters['quick'] )
        {
            $parentObject =& $node->attribute( 'object' );
            $sectionID =& $parentObject->attribute( 'section_id' );
        }
        while ( $parameters['structure'][$nodeID] > 0 )
        {
            // create object
            $object =& $class->instantiate();
            if ( $object )
            {
                $db->begin();

                $objectID = $object->attribute( 'id' );

                $nodeAssignment = eZNodeAssignment::create( array( 'contentobject_id' => $objectID,
                                                                   'contentobject_version' => 1,
                                                                   'parent_node' => $nodeID,
                                                                   'is_main' => 1 ) );
                $nodeAssignment->store();
                $dataMap =& $object->dataMap();
                     
                foreach( array_keys( $dataMap ) as $key )
                {
                    $attribute =& $dataMap[$key];
                    $classAttributeID = $attribute->attribute( 'contentclassattribute_id' );
                    if ( isset( $parameters['attributes'][$classAttributeID] ) )
                    {
                        $attributeParameters = $parameters['attributes'][$classAttributeID];
                        $dataType = $attribute->attribute( 'data_type_string' );

                        switch ( $dataType )
                        {
                            case 'ezstring':
                            {
                                $attribute->setAttribute( 'data_text', 
                                                          eZLoremIpsum::generateString( $attributeParameters['min_words'], $attributeParameters['max_words'] ) );
                            } break;

                            case 'ezxmltext':
                            {
                                $xml = '<?xml version="1.0" encoding="utf-8"?>'."\n".
                                       '<section xmlns:image="http://ez.no/namespaces/ezpublish3/image/"'."\n".
                                       '         xmlns:xhtml="http://ez.no/namespaces/ezpublish3/xhtml/"'."\n".
                                       '         xmlns:custom="http://ez.no/namespaces/ezpublish3/custom/">'."\n".
                                       '  <section>'."\n";
                                $numPars = mt_rand( ( int ) $attributeParameters['min_pars'], ( int ) $attributeParameters['max_pars'] );
                                for ( $par = 0; $par < $numPars; $par++ )
                                {
                                    $xml .= '    <paragraph>';
                                    $numSentences = mt_rand( ( int ) $attributeParameters['min_sentences'], ( int ) $attributeParameters['max_sentences'] );
                                    for ( $sentence = 0; $sentence < $numSentences; $sentence++ )
                                    {
                                        if ( $sentence != 0 )
                                        {
                                            $xml .= ' ';
                                        }
                                        $xml .= eZLoremIpsum::generateSentence();
                                    }
                                    $xml .= "</paragraph>\n";
                                }
                                $xml .= "  </section>\n</section>\n";
                                
                                $attribute->setAttribute( 'data_text', $xml );
                            } break;

                            case 'eztext':
                            {
                                $numPars = mt_rand( ( int ) $attributeParameters['min_pars'], ( int ) $attributeParameters['max_pars'] );
                                for ( $par = 0; $par < $numPars; $par++ )
                                {
                                    if ( $par == 0 )
                                    {
                                        $text = '';
                                    }
                                    else
                                    {
                                        $text .= "\n";
                                    }
                                    $numSentences = mt_rand( ( int ) $attributeParameters['min_sentences'], ( int ) $attributeParameters['max_sentences'] );
                                    for ( $sentence = 0; $sentence < $numSentences; $sentence++ )
                                    {
                                        $text .= eZLoremIpsum::generateSentence();
                                    }
                                    $text .= "\n";
                                }

                                $attribute->setAttribute( 'data_text', $text );
                            } break;

                            case 'ezboolean':
                            {
                                $rnd = mt_rand( 0, 99 );
                                $value = 0;
                                if ( $rnd < $attributeParameters['prob'] )
                                {
                                    $value = 1;
                                }

                                $attribute->setAttribute( 'data_int', $value );
                            } break;

                            case 'ezinteger':
                            {
                                $integer = mt_rand( ( int ) $attributeParameters['min'], ( int ) $attributeParameters['max'] );
                                $attribute->setAttribute( 'data_int', $integer );
                            } break;

                            case 'ezfloat':
                            {
                                $power = 100;
                                $float = mt_rand( $power * ( int ) $attributeParameters['min'], $power * ( int ) $attributeParameters['max'] );
                                $float = $float / $power;
                                $attribute->setAttribute( 'data_float', $float );
                            } break;

                            case 'ezprice':
                            {
                                $power = 10;
                                $price = mt_rand( $power * ( int ) $attributeParameters['min'], $power * ( int ) $attributeParameters['max'] );
                                $price = $price / $power;
                                $attribute->setAttribute( 'data_float', $price );
                            } break;

                            case 'ezuser':
                            {
                                $user =& $attribute->content();
                                if ( $user === null )
                                {
                                    $user = eZUser::create( $objectID );
                                }

                                $user->setInformation( $objectID,
                                                       md5( mktime() . '-' . mt_rand() ),
                                                       md5( mktime() . '-' . mt_rand() ) . '@ez.no',
                                                       'publish',
                                                       'publish' );
                                $user->store();
                            } break;

                            case 'ezuser':
                            {
                                $user =& $attribute->content();
                                if ( $user === null )
                                {
                                    $user = eZUser::create( $objectID );
                                }

                                $user->setInformation( $objectID,
                                                       md5( mktime . '-' . mtrand() ),
                                                       md5( mktime . '-' . mtrand() ) . '@ez.no',
                                                       'publish',
                                                       'publish' );
                                $user->store();
                            } break;
                        }

                        $attribute->store();
                    }
                }

                if ( isset( $parameters['quick'] ) && $parameters['quick'] )
                {
                    $version =& $object->version( 1 );

                    $version->setAttribute( 'status', 3 );
                    $version->store();

                    $object->setAttribute( 'status', 1 );
                    $objectName = $class->contentObjectName( $object );

                    $object->setName( $objectName, 1 );
                    $object->setAttribute( 'current_version', 1 );
                    $time = mktime();
                    $object->setAttribute( 'modified', $time );
                    $object->setAttribute( 'published', $time );
                    $object->setAttribute( 'section_id', $sectionID );
                    $object->store();

                    $newNode =& $node->addChild( $objectID, 0, true );
                    $newNode->setAttribute( 'contentobject_version', 1 );
                    $newNode->setAttribute( 'contentobject_is_published', 1 );
                    $newNode->setName( $objectName );
                    $newNode->setAttribute( 'main_node_id', $newNode->attribute( 'node_id' ) );
                    $newNode->setAttribute( 'sort_field', $nodeAssignment->attribute( 'sort_field' ) );
                    $newNode->setAttribute( 'sort_order', $nodeAssignment->attribute( 'sort_order' ) );
                               
                    $newNode->updateSubTreePath();
                    $newNode->store();

                    $db->commit();
                }
                else
                {
                    $db->commit();
                    if ( !eZOperationHandler::execute( 'content', 'publish', array( 'object_id' => $objectID, 'version' => 1 ) ) )
                    {
                        // TODO:
                        // add to the list of errors
                    }
                }
            }
            else
            {
                // TODO:
                // add to the list of errors
            }

            $parameters['structure'][$nodeID]--;
            $parameters['created_count']++;
            if ( time() - $startTime > 15 )
            {
                break;
            }
        }
    }

    if ( $parameters['created_count'] < $parameters['total_count'] )
    {
        $tpl->setVariable( 'parameters_serialized', serialize( $parameters ) );
        $parameters['time'] = time();
        $tpl->setVariable( 'parameters', $parameters );

        $Result['content'] =& $tpl->fetch( 'design:loremipsum/progress.tpl' );
        $Result['pagelayout'] = 'loremipsum/progress_pagelayout.tpl';
        return;
    }

    if ( isset( $parameters['quick'] ) && $parameters['quick'] )
    {
        include_once( 'kernel/classes/ezcontentcachemanager.php' );
        eZContentCacheManager::clearAllContentCache();
    }
}

if ( $http->hasPostVariable( 'AddNodeButton' ) )
{
    include_once( 'kernel/classes/ezcontentclass.php' );
    include_once( 'kernel/classes/ezcontentbrowse.php' );

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

$Result['content'] =& $tpl->fetch( 'design:loremipsum/main.tpl' );
$Result['path'] = array( array( 'url' => false,
                                'text' => 'Lorem Ipsum Generator' ) );

?>