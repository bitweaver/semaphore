<?php
/**
 * @author   xing <xing@synapse.plus.com>
 * @version  $Revision: 1.2 $
 * @package  Pigeonholes
 * @subpackage functions
 */
global $gBitSystem, $gBitUser, $gLibertySystem;

$registerHash = array(
	'package_name' => 'semaphore',
	'package_path' => dirname( __FILE__ ).'/',
	'service'      => LIBERTY_SERVICE_ACCESS_CONTROL,
);
$gBitSystem->registerPackage( $registerHash );

if( $gBitSystem->isPackageActive( 'semaphore' )) {
	// include service functions
	require_once( SEMAPHORE_PKG_PATH.'Semaphore.php' );

	$gLibertySystem->registerService( LIBERTY_SERVICE_ACCESS_CONTROL, SEMAPHORE_PKG_NAME, array(
		// functions
		'content_load_sql_function' => 'semaphore_load_sql',
		'content_edit_function'     => 'semaphore_content_edit',
		'content_preview_function'  => 'semaphore_content_preview',
		'content_store_function'    => 'semaphore_content_store',

		// templates
		'content_icon_tpl'          => 'bitpackage:semaphore/semaphore_icon_inc.tpl',
	));
}
?>
