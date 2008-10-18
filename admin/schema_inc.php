<?php
global $gBitInstaller;

$gBitInstaller->registerPackageInfo( SEMAPHORE_PKG_NAME,
	array(
		'description' => "Display a warning when a user tries to edit content that is already being edited.",
		'license'     => '<a href="http://www.gnu.org/licenses/licenses.html#LGPL">LGPL</a>',
	)
);

$tables = array(
	'semaphore' => "
		content_id C(250) PRIMARY,
		user_id I4 NOTNULL,
		ip C(16) NOTNULL,
		created I8
		CONSTRAINT	', CONSTRAINT `semaphore_content_ref` FOREIGN KEY (`content_id`) REFERENCES `".BIT_DB_PREFIX."liberty_content` (`content_id`)'
	",
);

foreach( array_keys( $tables ) AS $tableName ) {
	$gBitInstaller->registerSchemaTable( SEMAPHORE_PKG_NAME, $tableName, $tables[$tableName], TRUE );
}

/*
// ### Sequences
$sequences = array();
$gBitInstaller->registerSchemaSequences( USERS_PKG_NAME, $sequences );

// ### Default Preferences
$gBitInstaller->registerPreferences( USERS_PKG_NAME, array() );

//$indices = array ();
$gBitInstaller->registerSchemaIndexes( USERS_PKG_NAME, $indices );

// ### Default Permissions
$gBitInstaller->registerUserPermissions( USERS_PKG_NAME, array() );
*/

?>
