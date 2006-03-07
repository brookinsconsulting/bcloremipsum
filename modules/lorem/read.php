<?php


$user = eZUser::currentUser();

$accessList = $user->hasAccessTo( 'lorem', 'read' );

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

$Result['content'] = $result;

?>