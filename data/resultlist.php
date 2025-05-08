<?php
	//ini_set("display_errors","On");
	session_start();
	ini_set('max_execution_time',0);
	ini_set('memory_limit',-1);

	include("../handlers/_generics.php");
	$con = new _init;
	$today = date('Y-m-d');

	switch($_REQUEST['displayType']) {
		case "1":
			list($dtf) = $con->getArray("SELECT DATE_SUB('$today',INTERVAL 7 DAY);");
			$f = " and b.so_date between '$dtf' and '$today' ";
		break;
		case "2":
			list($dtf) = $con->getArray("SELECT DATE_SUB('$today',INTERVAL 30 DAY);");
			$f = " and b.so_date between '$dtf' and '$today' ";
		break;
		case "3":
			$f = '';
		break;
		default:
			$f = "and b.so_date = '$today'";
		break;

	}

	//echo "SELECT record_id AS id, LPAD(a.so_no,6,0) AS sono, DATE_FORMAT(b.so_date,'%m/%d/%Y') AS sodate, b.patient_name AS pname, FLOOR(DATEDIFF(b.so_date,c.birthdate)/364.25) AS age, c.gender, c.employer, a.procedure, IF(a.released='Y','Yes','No') AS released, d.fullname AS rby, IF(release_date IS NOT NULL,DATE_FORMAT(release_date,'%m/%d/%Y'),'') AS rdate, released_to,a.code, a.serialno, a.so_no as xso FROM lab_samples a LEFT JOIN so_header b ON a.so_no = b.so_no AND a.branch = b.branch LEFT JOIN patient_info c ON b.patient_id = c.patient_id LEFT JOIN user_info d ON a.released_by = d.emp_id WHERE a.status = '4' and c.patient_id = '$_SESSION[pid]';";

	$data = array();
	$datares = $con->dbquery("SELECT record_id AS id, serialno, a.so_no AS order_no, DATE_FORMAT(b.so_date,'%m/%d/%Y') AS sodate, LPAD(b.patient_id,6,0) AS pid, b.patient_name AS pname, c.gender, FLOOR(DATEDIFF(b.so_date,c.birthdate)/364.25) AS age,  a.procedure, f.subcategory, a.code,b.trace_no FROM lab_samples a LEFT JOIN so_header b ON a.so_no = b.so_no AND a.branch = b.branch LEFT JOIN patient_info c ON b.patient_id = c.patient_id LEFT JOIN user_info d ON a.released_by = d.emp_id LEFT JOIN services_master e ON a.code = e.code LEFT JOIN options_servicesubcat f ON e.subcategory = f.id WHERE a.status = '4' AND c.patient_id = '$_SESSION[pid]';");
	
    while($row = $datares->fetch_array()){
		$row['sono'] = "<a onclick=\"#\" href=\"javascript: parent.viewSO('$row[sono]');\" style=\"text-decoration: none;\">$row[sono]</a>";
        $data[] = array_map('utf8_encode',$row);
	}
	$results = ["sEcho" => 1,"iTotalRecords" => count($data),"iTotalDisplayRecords" => count($data),"aaData" => $data];
	echo json_encode($results);

?>