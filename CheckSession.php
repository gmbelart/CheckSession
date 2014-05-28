<?php

/**
 * Check all active session (all user online),
 * remove user's session
 * 
 *
 * @author      Ahmad Hajar <karyakudi@gmail.com>
 * @copyright   2014 Ahmad Hajar
 * @version     1.0
 */

class CheckSession{
	/**
	 * remove session with last modified more than param
	 * 
	 * @param $minute Int number of minute
	 * 
	 */
	public static function removeSession($minute){
		foreach(new DirectoryIterator(session_save_path()) as $fileinfo){
			if(strpos($fileinfo->getFilename(), 'sess_') !== false){
				$data = self::unserializeSession(file_get_contents($fileinfo->getPathname()));
				if($fileinfo->getMTime() < strtotime('-' . $minute . ' minutes') && !empty($data)){
					unlink($fileinfo->getPathname());
				}
			}
		}
	}

	/**
	 * check is session full
	 * 
	 * @param $max Int number of max
	 *
	 * @return Boolean true if session == $max
	 *
	 */
	public static function isFull($max){
		if(empty($_SESSION) && self::countSession() == $max){
			return true;
		}
		return false;
	}

	/**
	 * count all active session (user online)
	 *  
	 * @return Int number of active session
	 * 
	 */
	public static function countSession(){
		$i = 0;
		foreach(new DirectoryIterator(session_save_path()) as $fileinfo){
			if(strpos($fileinfo->getFilename(), 'sess_') !== false){
				$data = self::unserializeSession(file_get_contents($fileinfo->getPathname()));
				if(!empty($data)){
					$i++;
				}
			}
		}
		return $i;
	}

	/**
	 * unserializesession from http://www.php.net/manual/en/function.session-decode.php#101687
	 * 
	 * @param String $data Raw of session file
	 * 
	 * @return Array of session
	 */
	public static function unserializeSession($data){
	    if(strlen($data) == 0){
	        return array();
	    }
	    
	    // match all the session keys and offsets
	    preg_match_all('/(^|;|\})([a-zA-Z0-9_]+)\|/i', $data, $matchesarray, PREG_OFFSET_CAPTURE);

	    $returnArray = array();

	    $lastOffset = null;
	    $currentKey = '';
	    foreach ( $matchesarray[2] as $value ){
	        $offset = $value[1];
	        if(!is_null( $lastOffset)){
	            $valueText = substr($data, $lastOffset, $offset - $lastOffset );
	            $returnArray[$currentKey] = unserialize($valueText);
	        }
	        $currentKey = $value[0];

	        $lastOffset = $offset + strlen( $currentKey )+1;
	    }

	    $valueText = substr($data, $lastOffset );
	    $returnArray[$currentKey] = unserialize($valueText);
	    
	    return $returnArray;
	}
}
