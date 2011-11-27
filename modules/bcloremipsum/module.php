<?php
/**
 * File containing the bcloremipsum module configuration file, module.php
 *
 * @copyright Copyright (C) 1999 - 2011 Brookins Consulting. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2 (or later)
 * @version //autogentag//
 * @package bcloremipsum
 */

// Define module name
$Module = array( 'name' => 'Create Nodes- Creator of content tree nodes for eZ Publish' );

// Define module view and parameters
$ViewList = array();

// Define create and read module view parameters
$ViewList['create'] = array(
          'script' => 'create.php',
          'default_navigation_part' => 'bcloremipsumnavigationpart',
          'ui_context' => 'administration',
          'params' => array(),
          'unordered_params' => array() );

$ViewList['read'] = array(
          'script' => 'read.php',
          'functions' => array( 'read' ),
          'default_navigation_part' => 'bcloremipsumnavigationpart',  
          'ui_context' => 'administration',
          'params' => array(),
          'unordered_params' => array() );

?>
