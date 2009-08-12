<?php

// Initialize English dictionary, or default to some latin
$englishDict = file( "extension/loremipsum/dictionary/dictionary.txt" );
if ( count ( $englishDict ) > 0 )
{
    $GLOBALS['eZLoremIpsumDictionary']  = $englishDict;
}
else
{
$GLOBALS['eZLoremIpsumDictionary'] = Array(
    'a', 'ab', 'ac', 'accumsan', 'ad', 'adipiscing', 'aenean', 'aliquam',
    'aliquet', 'amet', 'ante', 'aptent', 'arcu', 'as', 'at', 'auctor', 'augue',
    'bibendum', 'blandit', 'class', 'commodo', 'condimentum', 'congue',
    'consectetuer', 'consequat', 'conubia', 'convallis', 'cras', 'crlorem',
    'cubilia', 'cum', 'curabitur', 'curae', 'cursus', 'dapibus', 'diam',
    'dictum', 'dictumst', 'dignissim', 'dis', 'dolor', 'donec', 'dui', 'duis',
    'egestas', 'eget', 'eleifend', 'elementum', 'elit', 'enim', 'erat', 'eros',
    'est', 'et', 'etiam', 'eu', 'euismod', 'facilisi', 'facilisis', 'fames',
    'faucibus', 'felis', 'fermentum', 'feugiat', 'fringilla', 'fusce',
    'gravida', 'habitant', 'habitasse', 'hac', 'hendrerit', 'hymenaeos',
    'iaculis', 'id', 'imperdiet', 'in', 'inceptos', 'integer', 'interdum',
    'ipsum', 'justo', 'lacinia', 'lacus', 'laoreet', 'lectus', 'leo', 'libero',
    'ligula', 'litora', 'lobortis', 'lorem', 'luctus', 'maecenas', 'magna',
    'magnis', 'malesuada', 'massa', 'mattis', 'mauris', 'metus', 'mi',
    'molestie', 'mollis', 'montes', 'morbi', 'mus', 'nam', 'nascetur',
    'natoque', 'nec', 'neque', 'netus', 'nibh', 'nisi', 'nisl', 'non',
    'nonummy', 'nostra', 'nulla', 'nullam', 'nunc', 'odio', 'orci', 'ornare',
    'parturient', 'pede', 'pellentesque', 'penatibus', 'per', 'pharetra',
    'phasellus', 'placerat', 'platea', 'porta', 'porttitor', 'posuere',
    'potenti', 'praesent', 'pretium', 'primis', 'proin', 'pulvinar', 'purus',
    'quam', 'quis', 'quisque', 'rhoncus', 'ridiculus', 'risus', 'rutrum',
    'sagittis', 'sapien', 'scelerisque', 'sed', 'sem', 'semper', 'senectus',
    'sit', 'sociis', 'sociosqu', 'sodales', 'sollicitudin', 'suscipit',
    'suspendisse', 'taciti', 'tellus', 'tempor', 'tempus', 'tincidunt',
    'torquent', 'tortor', 'tristique', 'turpis', 'ullamcorper', 'ultrices',
    'ultricies', 'urna', 'ut', 'varius', 'vehicula', 'vel', 'velit',
    'venenatis', 'vestibulum', 'vitae', 'vivamus', 'viverra', 'volutpat',
    'vulputate' );
 }


class eZLoremIpsum
{
    static function generateWord()
    {
        $dictionary = $GLOBALS['eZLoremIpsumDictionary'];

        $dictionarySize = count( $dictionary );
        $randomIndex = mt_rand( 0, $dictionarySize - 1 );
        return $dictionary[$randomIndex];
    }

    static function generateString( $minWords = 4, $maxWords = 6, $capitalize = true )
    {
        $string = '';
        $numberOfWords = mt_rand( $minWords, $maxWords );
        for ( $index = 0; $index < $numberOfWords; $index++ )
        {
            $word = eZLoremIpsum::generateWord();
            if ( !$string )
            {
                if ( $capitalize )
                {
                    $string = ucfirst( $word );
                }
                else
                {
                    $string = $word;
                }
            }
            else
            {
                $string .= ' '.$word;
            }
        }

        return $string;
    }

    static function generateSentence()
    {
        $sentence = '';

        // TODO: make parameters be configurable
        // with probablity of 0.8 the sentence will be single
        $numberOfParts = ( mt_rand( 0, 100 ) < 80 )? 1: 2;
        for( $part = 0; $part < $numberOfParts; $part++ )
        {
            // single sentence has from 5 to 9 words
            $sentence .= eZLoremIpsum::generateString( 5, 9, ( $part == 0 )? true: false );
            if ( $part < $numberOfParts - 1 )
            {
                $sentence .= ', ';
            }
        }
        // 10% of sentences wil be finished with exclamation mark
        $sentence .= ( mt_rand( 0, 100 ) < 90 )? '.': '!';

        return $sentence;
    }
    /**
     * Set object creation parameters to some hardcoded values.
     */
    static function generateAttributeParameters( &$parameters )
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
                    $attributeParameters['min_words'] = 2;
                    $attributeParameters['max_words'] = 5;
                    break;

                case 'ezkeyword':
                    $attributeParameters['min_words'] = 2;
                    $attributeParameters['max_words'] = 5;
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

                // Default 100% probability to insert an image and 1-4 words as the image alt text
                case 'ezimage':
                    $attributeParameters['prob'] = 100;
                    $attributeParameters['min_words'] = 1;
                    $attributeParameters['max_words'] = 4;
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

    /*!
     Create objects

     \param $parameters.

     \returm $parameters, with updated counts.
    */
    static function createObjects( $parameters )
    {

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

        $cli = false;
        if ( isset( $parameters['cli'] ) && $parameters['cli'] )
            $cli = $parameters['cli'];

        $classID = $parameters['class'];

        if ( !$class = eZContentClass::fetch( $classID ) )
        {
            throw new Exception( "Class " .$classID." not found." );
        }

        if ( !$attributes = eZContentClassAttribute::fetchListByClassID( $classID, eZContentClass::VERSION_STATUS_DEFINED, false ) )
        {
            throw new Exception( "ClassID " .$classID." has no attributes." );
        }

        foreach ( $attributes as $attribute )
        {
            if ( $attribute['is_required'] && !isset( $parameters['attributes'][$attribute['id']] ) )
            {
                throw new Exception( "AttributeID " .$attribute['id']." is required, but has no content." );
            }
        }

        $db = eZDB::instance();
        $db->setIsSQLOutputEnabled( false );

        $startTime = time();
        foreach ( array_keys( $parameters['structure'] ) as $nodeID )
        {
            $node = eZContentObjectTreeNode::fetch( $nodeID );
            if ( !$node )
            {
                throw new Exception( "NodeID $nodeID not found." );
            }
            if ( isset( $parameters['quick'] ) && $parameters['quick'] )
            {
                $parentObject = $node->attribute( 'object' );
                $sectionID = $parentObject->attribute( 'section_id' );
            }
            while ( $parameters['structure'][$nodeID] > 0 )
            {
                // create object
                $object = $class->instantiate();
                if ( $object )
                {
                    $db->begin();

                    $objectID = $object->attribute( 'id' );

                    if ( $cli )
                        $cli->output( "Creating object #$objectID" );

                    $nodeAssignment = eZNodeAssignment::create( array( 'contentobject_id' => $objectID,
                                                                       'contentobject_version' => 1,
                                                                       'parent_node' => $nodeID,
                                                                       'is_main' => 1 ) );
                    $nodeAssignment->store();
                    $dataMap = $object->dataMap();

                    foreach( array_keys( $dataMap ) as $key )
                    {
                        $attribute = $dataMap[$key];
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

                                case 'ezkeyword':
                                {
                                    $tags = eZLoremIpsum::generateString( $attributeParameters['min_words'], $attributeParameters['max_words'] );

                                    $tagArray = explode( " ", $tags );
                                    $data = implode( ", ", $tagArray );
                                    $keyword = new eZKeyword();
                                    $keyword->initializeKeyword( $data );
                                    $attribute->setContent( $keyword );
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
                                        if ( ( $numPars > 1 ) && ( $par > 0 ) )
                                        {
                                            $xml .= "<header>" . eZLoremIpsum::generateSentence() . "</header>\n";
                                        }

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


                                case 'ezimage':
                                {
                                    $rnd = mt_rand( 0, 99 );
                                    $value = 0;
                                    if ( $rnd < $attributeParameters['prob'] )
                                    {
                                        $value = 1;
                                    }

                                    // insert image
                                    if ( $value )
                                    {
                                        // List the available images to choose from
                                        $images = array();
                                        $handler = opendir( "extension/loremipsum/images/" );
                                        while ( $image = readdir( $handler ) )
                                        {
                                            if ( $image != '.' && $image != '..')
                                                $images[] = $image;
                                        }
                                        closedir( $handler );

                                        // Randomly select an image from the image pool
                                        $r = count( $images ) - 1;
                                        $index = rand( 0, $r );
                                        $selectedImage = $images[$index];


                                        $filePath = "extension/loremipsum/images/" . $selectedImage;
                                        $imageContent = $attribute->attribute( 'content' );
                                        $imageContent->initializeFromFile( $filePath, false, basename( $filePath ) );
                                        $imageContent->store( $attribute );
                                        $attribute->store();
                                    }
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
                                    $user = $attribute->content();
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
                                    $user = $attribute->content();
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
                        $version = $object->version( 1 );

                        $version->setAttribute( 'status', 3 );
                        $version->store();

                        $object->setAttribute( 'status', 1 );
                        $objectName = $class->contentObjectName( $object );

                        $object->setName( $objectName, 1 );
                        $object->setAttribute( 'current_version', 1 );
                        $time = time();
                        $object->setAttribute( 'modified', $time );
                        $object->setAttribute( 'published', $time );
                        $object->setAttribute( 'section_id', $sectionID );
                        $object->store();

                        $newNode = $node->addChild( $objectID, true );
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

                if ( !$cli && ( time() - $startTime > 15 ) )
                {
                    break;
                }
            }
        }

        if ( isset( $parameters['quick'] ) && $parameters['quick'] )
        {
            eZContentCacheManager::clearAllContentCache();
        }

        $parameters['used_time'] = time() - $parameters['start_time'];
        return $parameters;
    }
}

?>