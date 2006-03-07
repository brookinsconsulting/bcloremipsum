<?php

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

class eZLoremIpsum
{
    function generateWord()
    {
        $dictionary =& $GLOBALS['eZLoremIpsumDictionary'];
        $dictionarySize = count( $dictionary );
        $randomIndex = mt_rand( 0, $dictionarySize - 1 );
        return $dictionary[$randomIndex];
    }

    function generateString( $minWords = 4, $maxWords = 6, $capitalize = true )
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

    function generateSentence()
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
}

?>
