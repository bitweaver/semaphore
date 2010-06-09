<?php
/**
 * @version  $Header$
 * @package  semaphore
 */
$limit = array(
	'60'  =>  1,
	'120' =>  2,
	'180' =>  3,
	'240' =>  4,
	'300' =>  5,
	'360' =>  6,
	'420' =>  7,
	'480' =>  8,
	'540' =>  9,
	'600' => 10,
	'900' => 15,
);
$gBitSmarty->assign( 'limit', $limit );

if( !empty( $_REQUEST['change_prefs'] )) {
	simple_set_int( 'semaphore_limit', SEMAPHORE_PKG_NAME );
}
?>
