<?php
$limit = array(
	'60'  =>  1,
	'120' =>  2,
	'180' =>  3,
	'240' =>  4,
	'300' =>  5,
	'600' => 10,
	'900' => 15,
);
$gBitSmarty->assign( 'limit', $limit );

if( !empty( $_REQUEST['change_prefs'] )) {
	simple_set_int( 'semaphore_limit', SEMAPHORE_PKG_NAME );
}
?>
