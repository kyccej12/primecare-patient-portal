<?php
	
	require_once 'handlers/initDB.php';
	
	class authenticate extends myDB {
		function generateUniqueId($maxLength = null) {
			$entropy = '';
			if (function_exists('openssl_random_pseudo_bytes')) {
				$entropy = openssl_random_pseudo_bytes(64, $strong);
				if($strong !== true) {
					$entropy = '';
				}
			}
				$entropy .= uniqid(mt_rand(), true);
			if (class_exists('COM')) {
				try {
					$com = new COM('CAPICOM.Utilities.1');
					$entropy .= base64_decode($com->GetRandom(64, 0));
					} catch (Exception $ex) {
				}
			}
				
			if (is_readable('/dev/urandom')) {
				$h = fopen('/dev/urandom', 'rb');
				$entropy .= fread($h, 64);
				fclose($h);
			}

			$hash = hash('whirlpool', $entropy);
			if ($maxLength) {
				return substr($hash, 0, $maxLength);
			}
				return $hash;
		}
		
		function verify($uname,$pass) {
			if(!empty($uname) && !empty($pass)) {

				$pid = substr($uname,1,6);
				//echo "select count(*) from patient_info where patient_id ='$pid' and birthdate = '$pass';";
				list($isExist) = parent::getArray("select count(*) from patient_info where patient_id ='$pid' and birthdate = '$pass';");
				if($isExist > 0) {
					$this->storeSession($pid);
					return true;
				} else {
						return false;
				}
			} else { return false; }
		} 
		

		function storeSession($pid) {
			$skey = $this->generateUniqueId(32);
			parent::dbquery("insert ignore into online_active_sessions (userid,timestamp,sessid) values ('$pid','".time()."','$skey');");
			parent::dbquery("update patient_info set last_logged_in=now() where patient_id = '$pid';");
			
			
			/* Store Session Values */
			session_start();
			$_SESSION['pid'] = $pid;
			$_SESSION['authkey'] = $skey;
		}
	}
	
	$auth = new authenticate();
	if($auth->verify($_POST['uname'],$_POST['pass']) == true) {

		***REMOVED***
        ***REMOVED***
        ***REMOVED***
        ***REMOVED***
        ***REMOVED***
        ***REMOVED***

		
		$URL = $HTTP_REFERER . "index.php";
	

	} else {
		$URL = $HTTP_REFERER . "login.php";
	}
	
	echo $URL;

	//header("Location: $URL");
	exit();
?>
