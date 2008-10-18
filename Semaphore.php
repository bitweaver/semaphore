<?php
/**
 * Management of Liberty content
 *
 * @package  semaphore
 * @version  $Header: /cvsroot/bitweaver/_bit_semaphore/Semaphore.php,v 1.1 2008/10/18 17:03:08 squareing Exp $
 */

/**
 * required setup
 */
require_once( KERNEL_PKG_PATH.'BitBase.php' );

class Semaphore extends BitBase {
	/**
	 * Semaphore Initialisation
	 */
	function Semaphore() {
		BitBase::BitBase();
	}

	/**
	 * storeSemaphore 
	 * 
	 * @param array $pContentId 
	 * @access public
	 * @return TRUE on success, FALSE on failure - mErrors will contain reason for failure
	 */
	function storeSemaphore( $pContentId ) {
		if( !empty( $pContentId )) {
			global $gBitSystem, $gBitUser;

			// we can only have one semaphore per content_id - the most recent one is the one we need
			$query = "DELETE FROM `".BIT_DB_PREFIX."semaphore` WHERE `content_id` = ?";
			$this->mDb->query( $query, array( $pContentId ));

			$storeHash = array(
				'content_id' => $pContentId,
				'user_id'    => ( @BitBase::verifyId( $gBitUser->mUserId ) ? $gBitUser->mUserId : ANONYMOUS_USER_ID ),
				'ip'         => $gBitUser->mInfo['ip'],
				'created'    => $gBitSystem->getUTCTime(),
			);
			$this->mDb->associateInsert( BIT_DB_PREFIX."semaphore", $storeHash );
			return $gBitSystem->getUTCTime();
		}
	}
}

// ============================== Service Functions ==============================
/**
 * semaphore_load_sql 
 * 
 * @param array $pObject Currently loaded object
 * @param array $pParamHash 
 * @access public
 * @return TRUE on success, FALSE on failure - mErrors will contain reason for failure
 */
function semaphore_load_sql( &$pObject, $pParamHash = NULL ) {
	global $gBitSystem;
	$limit = $gBitSystem->getUTCTime() - $gBitSystem->getConfig( 'semaphore_limit', 120 );
	$ret['select_sql'] = ", sem.`created` AS semaphore_created, sem.`ip` AS semaphore_ip, sem.`user_id` AS semaphore_user_id";
	$ret['join_sql']   = "LEFT OUTER JOIN `".BIT_DB_PREFIX."semaphore` sem ON( lc.`content_id` = sem.`content_id` AND sem.`created` > ".$limit." )";
	return $ret;
}

/**
 * semaphore_content_edit 
 * 
 * @param array $pObject Currently loaded object
 * @access public
 * @return TRUE on success, FALSE on failure - mErrors will contain reason for failure
 */
function semaphore_content_edit( &$pObject ) {
	global $gBitSystem, $gBitSmarty, $gBitUser;
	if( is_object( $pObject ) && $pObject->isValid() ) {
		// we use the IP address here since it allows us to distinguish between anonymous users as well
		//if( !empty( $pObject->mInfo['semaphore_created'] ) && $pObject->mInfo['semaphore_ip'] != $gBitUser->mInfo['ip'] ) {
		if( !empty( $pObject->mInfo['semaphore_created'] ) && $pObject->mInfo['semaphore_user_id'] != $gBitUser->mUserId ) {
			$semaphore_user = $gBitUser->getDisplayName( FALSE, $gBitUser->getUserInfo( array( 'user_id' => $pObject->mInfo['semaphore_user_id'] )));
			$gBitSystem->setOnloadScript( "javascript:alert('".tra( "Some other user ($semaphore_user) might be editing this content. Continue at your own peril." )."');" );
			$gBitSmarty->assign( 'serviceOnsubmit', "return confirm('".tra( "Some other user ($semaphore_user) might be editing this content. Continue at your own peril." )."')" );
		} else {
			$semaphore = new Semaphore();
			$semaphore->storeSemaphore( $pObject->mContentId );
		}
	}
}

/**
 * semaphore_content_preview 
 * 
 * @param array $pObject Currently loaded object
 * @access public
 * @return TRUE on success, FALSE on failure - mErrors will contain reason for failure
 */
function semaphore_content_preview( &$pObject ) {
	global $gBitSystem, $gBitSmarty, $gBitUser;
	if( is_object( $pObject ) && $pObject->isValid() ) {
		// we use the IP address here since it allows us to distinguish between anonymous users as well
		//if( !empty( $pObject->mInfo['semaphore_created'] ) && $pObject->mInfo['semaphore_ip'] != $gBitUser->mInfo['ip'] ) {
		if( !empty( $pObject->mInfo['semaphore_created'] ) && $pObject->mInfo['semaphore_user_id'] != $gBitUser->mUserId ) {
			$semaphore_user = $gBitUser->getDisplayName( FALSE, $gBitUser->getUserInfo( array( 'user_id' => $pObject->mInfo['semaphore_user_id'] )));
			$gBitSmarty->assign( 'serviceOnsubmit', "return confirm('".tra( "Some other user ($semaphore_user) might be editing this content. Continue at your own peril." )."')" );
		} else {
			$semaphore = new Semaphore();
			$semaphore->storeSemaphore( $pObject->mContentId );
		}
	}
}
?>
