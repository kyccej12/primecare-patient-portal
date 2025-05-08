<?php
	
	require_once "initDB.php";
	class _init extends myDB {
	
		public $pageNum;
		public $cpass;
		public $exception;

		public function _toHrs($_x) {
			return ROUND($_x / 3600,2);
		}
		
		public function renew_timestamp($key,$time) {
			$v = parent::dbquery("update active_sessions set timestamp = '$time' where sessid = '$key';");
			if($v) { return true; }
		}
		
		function queryOffset($rowsPerPage,$page) {
			if($page > 1) { $this->pageNum = $page; } else { $this->pageNum = 1; }
			$offset = ($this->pageNum - 1) * $rowsPerPage;
			return $offset;
		}
		
		function generateRandomString($length = 64) {
			return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
		}

		function getUname($uid) {
			list($name) = parent::getArray("select fullname from user_info where emp_id = '$uid';");
			return $name;
		}
		
		function validateKey() {
			$tcur = time();
			
			list($_sess) = parent::getArray("select count(*) from online_active_sessions where sessid = '$_SESSION[authkey]';");
			if($_sess > 0) {
				list($tstamp) = parent::getArray("select `timestamp` from online_active_sessions where sessid = '$_SESSION[authkey]';");
				$life = $tcur - $tstamp;
				if($life > 7200) {
					$this->exception = 2;
					unset($_SESSION['userid']);
					unset($_SESSION['authkey']);
					session_destroy();
					parent::dbquery("delete from online_active_sessions where sessid = '$_SESSION[authkey]';");
				} else {
					if($this->renew_timestamp($_SESSION['authkey'],$tcur) == true) { $this->exception = 0; } else { $this->exception = 3; }
				}
			} else {
				$this->exception = 4;
			}
		}

		public function updateTStamp($skey) {
			parent::dbquery("update ignore online_active_sessions set `timestamp` = '".time()."' where sessid = '$skey';");
		}
		
		function initBackground($i) {
			if($i%2==0){ $bgC = "#ededed"; } else { $bgC = "#ffffff"; }
			return $bgC;
		}

	
		
		function formatDate($date) {
			$date = explode("/",$date);
			return $date[2]."-".$date[0]."-".$date[1];
		}
		
		function formatDigit($dig) {
			return preg_replace('/,/','',$dig);
		}
		
		function formatNumber($num, $dec) {
			if($num=='') { $num = 0; }
			if($num < 0) {
				return '('.number_format(abs($num),$dec).')';
			} else {
				return number_format($num,$dec);
			}
		}
	
		function checkChemValues($age,$gender,$code,$result) {
			if($result > 0) {
				$att = parent::getArray("SELECT * FROM lab_testvalues where `code` = '$code';");
				if($age <= 17) {
					if($result < $att['p_min_value']) { return "<font color=red><b>L</b></font>"; }
					if($result > $att['p_max_value']) { return "<font color=red><b>H</b></font>"; }
				
				} else {
					if($gender == 'M') {
						if($result < $att['min_value']) { return "<font color=red><b>L</b></font>"; }
						if($result > $att['max_value']) { return "<font color=red><b>H</b></font>"; }
					} else {
						if($result < $att['f_min_value']) { return "<font color=red><b>L</b></font>"; }
						if($result > $att['f_max_value']) { return "<font color=red><b>H</b></font>"; }
					}
				}
			}
		}

		function getAttribute($code,$age,$gender) {
			
			$att = parent::getArray("SELECT unit,`min_value`,`max_value`,f_min_value,f_max_value,p_min_value,p_max_value FROM lab_testvalues WHERE `code` = '$code';");

			if($age <= 16) {
				if($att['p_min_value'] != '' || $att['p_max_value'] !='') {
					$testAttribute = $att['p_min_value']	. " - " . $att['p_max_value'] . " " . $att['unit'];	
				} else {
					$testAttribute = $att['min_value']	. " - " . $att['max_value'] . " " . $att['unit'];	
				}
			} else {
				if($gender == 'M') {
					$testAttribute = $att['min_value']	. " - " . $att['max_value'] . " " . $att['unit'];	
				} else {
					$testAttribute = $att['f_min_value']	. " - " . $att['f_max_value'] . " " . $att['unit'];	
				}
			}

			return $testAttribute;
			
		}

		function getCBCAttribute($age,$gender,$attr) {
			
			$att = parent::getArray("SELECT unit, if(multiplier!='',concat('x',multiplier),'') as multiplier, format(`min_value`,place_values) as `min_value`,format(`max_value`,place_values) as `max_value`,format(f_min_value,place_values) as f_min_value,format(f_max_value,place_values) as f_max_value,format(p_min_value,place_values) as p_min_value,format(p_max_value,place_values) as p_max_value,format(p_f_min_value,place_values) as p_f_min_value,format(p_f_max_value,place_values) as p_f_max_value FROM lab_cbc_defvalues where attribute = '$attr';");

			if($age <= 16) {

				if($gender == 'M') {
					$testAttribute = $att['p_min_value']	. " - " . $att['p_max_value'] . "" . $att['multiplier'] . "" . $att['unit'];	
				} else {
					$testAttribute = $att['p_f_min_value']	. " - " . $att['p_f_max_value'] . "" . $att['multiplier'] . "" . $att['unit'];	

				}

			} else {
				if($gender == 'M') {
					$testAttribute = $att['min_value']	. " - " . $att['max_value'] . "" . $att['multiplier'] . "" . $att['unit'];	
				} else {
					$testAttribute = $att['f_min_value']	. " - " . $att['f_max_value'] . "" . $att['multiplier'] . "" . $att['unit'];	
				}
			}

			return $testAttribute;
			
		}

		function checkCBCValues($age,$gender,$attr,$result) {
			$att = parent::getArray("SELECT * FROM lab_cbc_defvalues where attribute = '$attr';");

			if($age <= 16) {
				if($gender == 'M') {
					if($result < $att['p_min_value'] || $result >= $att['p_max_value']) { return "*"; }
				} else {
					if($result < $att['p_f_min_value'] || $result >= $att['p_f_max_value']) { return "*"; }	
				}
			} else {
				if($gender == 'M') {
					if($result < $att['min_value'] || $result >= $att['max_value']) { return "*"; }
				} else {
					if($result < $att['f_min_value'] || $result >= $att['f_max_value']) { return "*"; }
				}
			}
		}

		function calculateAge($dob){
			$today = date('Y-m-d');
			list($age) = parent::getArray("SELECT FLOOR(ROUND(DATEDIFF('$today','$dob') / 364.25,2));");
			return $age;
		}

	}
?>