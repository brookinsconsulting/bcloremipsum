<?php
/**
 * File containing the bcloremipsum/read module view.
 *
 * @copyright Copyright (C) 1999 - 2011 Brookins Consulting. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2 (or later)
 * @version //autogentag//
 * @package bcloremipsum
 */
 
/**
 * Fetch current user and related attributes
 */
$user = eZUser::currentUser();
$accessList = $user->hasAccessTo( 'lorem', 'read' );

/**
 * Test that the current user has access and specifically what access
 */
switch( $accessList['accessWord'] )
{
    case 'no':
    {
        $result = 'No access';
    } break;
	
    case 'yes':
    {
        $result = 'Full access';
    } break;

    case 'limited':
    {
        $result = 'Limited : ' . var_export( $accessList['policies'], 1 );

        foreach( $accessList['policies'] as $policy => $limitationList )
        {
            foreach( $limitationList as $limitationName => $limitationValue )
            {
                if ( in_array( 1, $limitationValue ) )
                {
                    $result .= '<br /> OK';
                }
            }
        }
    } break;
}

/**
 * Assign the module view results
 */
$Result['content'] = $result;

?>
