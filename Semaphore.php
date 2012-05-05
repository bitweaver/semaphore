<?php
/**
 * Management of Liberty content
 *
 * @package  semaphore
 * @version  $Header$
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
		parent::__construct();
	}

	/**
	 * store 
	 * 
	 * @param numeric $pContentId 
	 * @access public
	 * @return ADO result set on success, FALSE on failure
	 */
	function store( $pContentId ) {
		if( @BitBase::verifyId( $pContentId )) {
			global $gBitSystem, $gBitUser;

			// we can only have one semaphore per content_id - the most recent one is the one we need
			$this->expunge( $pContentId );

			$storeHash = array(
				'content_id' => $pContentId,
				'user_id'    => ( @BitBase::verifyId( $gBitUser->mUserId ) ? $gBitUser->mUserId : ANONYMOUS_USER_ID ),
				'ip'         => $gBitUser->mInfo['ip'],
				'created'    => $gBitSystem->getUTCTime(),
			);
			return $this->mDb->associateInsert( BIT_DB_PREFIX."semaphore", $storeHash );
		}
	}

	/**
	 * expunge 
	 * 
	 * @param numeric $pContentId 
	 * @access public
	 * @return ADO result set on success, FALSE on failure
	 */
	function expunge( $pContentId ) {
		if( @BitBase::verifyId( $pContentId )) {
			$query = "DELETE FROM `".BIT_DB_PREFIX."semaphore` WHERE `content_id` = ?";
			$this->mDb->query( $query, array( $pContentId ));
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
 * @return void
 */
function semaphore_load_sql( &$pObject, $pParamHash = NULL ) {
	global $gBitSystem, $gBitUser;
	$limit = $gBitSystem->getUTCTime() - $gBitSystem->getConfig( 'semaphore_limit', 300 );
	$ret['select_sql'] = ", sem.`created` AS semaphore_created, sem.`ip` AS semaphore_ip, sem.`user_id` AS semaphore_user_id";
	$ret['join_sql']   = "LEFT OUTER JOIN `".BIT_DB_PREFIX."semaphore` sem ON( lc.`content_id` = sem.`content_id` AND sem.`created` > ".$limit." AND sem.`user_id` <> ".$gBitUser->mUserId." )";
	return $ret;
}

/**
 * semaphore_content_edit 
 * 
 * @param array $pObject Currently loaded object
 * @access public
 * @return void
 */
function semaphore_content_edit( &$pObject ) {
	semaphore_content_edit_preview( FALSE, $pObject );
}

/**
 * semaphore_content_preview 
 * 
 * @param array $pObject Currently loaded object
 * @access public
 * @return void
 */
function semaphore_content_preview( &$pObject ) {
	semaphore_content_edit_preview( TRUE, $pObject );
}

/**
 * semaphore_content_edit_preview Handle edit and preview situation
 * 
 * @param array $pObject Currently loaded object
 * @access public
 * @return void
 */
function semaphore_content_edit_preview( $pPreview = FALSE, &$pObject ) {
	global $gBitSystem, $gBitSmarty, $gBitUser;
	if( is_object( $pObject ) && $pObject->isValid() ) {
		// we use the IP address here since it allows us to distinguish between anonymous users as well
		//if( !empty( $pObject->mInfo['semaphore_created'] ) && $pObject->mInfo['semaphore_ip'] != $gBitUser->mInfo['ip'] ) {
		if( !empty( $pObject->mInfo['semaphore_created'] ) && $pObject->mInfo['semaphore_user_id'] != $gBitUser->mUserId ) {
			$semaphore_user = $gBitUser->getDisplayName( FALSE, $gBitUser->getUserInfo( array( 'user_id' => $pObject->mInfo['semaphore_user_id'] )));
			if( !$pPreview ) {
				$gBitSystem->setOnloadScript( "javascript:alert('".tra( "Some other user might be editing this content" )."($semaphore_user). ".tra( "Continue at your own peril." )."');" );
			}
			$gBitSmarty->assign( 'serviceOnsubmit', "return confirm('".tra( "Some other user might be editing this content" )."($semaphore_user). ".tra( "Continue at your own peril." )."')" );
		} else {
			$semaphore = new Semaphore();
			$semaphore->store( $pObject->mContentId );
		}
	}
}

/**
 * semaphore_content_store When content is stored, we need to remove the semaphore from the database
 * 
 * @param array $pObject Currently loaded object
 * @access public
 * @return void
 * @note We can't use the semaphore_content_store to display a warning since it's called to late - half way through the store process
 */
function semaphore_content_store( &$pObject ) {
	if( is_object( $pObject ) && $pObject->isValid() ) {
		$semaphore = new Semaphore();
		$semaphore->expunge( $pObject->mContentId );
	}
}
?>
