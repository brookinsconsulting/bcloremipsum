<?php

$Module = array( 'name' => 'Lorem Ipsum Generator for eZ publish' );

$ViewList = array();
$ViewList['ipsum'] = array(
    'script' => 'ipsum.php',
    'default_navigation_part' => 'ezloremipsumpart',    
    'params' => array() );

$ViewList['read'] = array(
    'script' => 'read.php',
    'functions' => array( 'read' ),
    'default_navigation_part' => 'ezloremipsumpart',    
    'params' => array() );


$FunctionList['read'] = array( 'Ipsum' => array( 'name' => 'Ipsum',
                                                 'values' => array( array( 'Name' => 'One', 'value' => 1 ),
                                                                    array( 'Name' => 'Two', 'value' => 2 ),
array( 'Name' => 'Three', 'value' => 3 ) ) ) );


?>
