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
		content_id I4 PRIMARY,
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
### Sequences
$gBitInstaller->registerSchemaSequences( USERS_PKG_NAME, array() );

### Default Preferences
$gBitInstaller->registerPreferences( USERS_PKG_NAME, array() );

### Indicies
$gBitInstaller->registerSchemaIndexes( USERS_PKG_NAME, array() );

### Default Permissions
$gBitInstaller->registerUserPermissions( USERS_PKG_NAME, array() );
*/

?>
