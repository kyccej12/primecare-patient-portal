<?php
	session_start();
	include("../lib/mpdf6/mpdf.php");
	include("../handlers/_generics.php");

	$con = new _init;

/* MYSQL QUERIES SECTION */
	$now = date("m/d/Y h:i a");
	$co = $con->getArray("select * from companies where company_id = '$_SESSION[company]';");

	$_ihead = $con->getArray("SELECT DATE_FORMAT(result_date,'%m/%d/%Y') AS rdate, b.patient_name, b.patient_address, c.gender as xgender, IF(c.gender='M','Male','Female') AS gender, FLOOR(DATEDIFF(so_date,c.birthdate)/364.25) AS age, b.physician, a.serialno, a.created_by, a.verified, a.verified_by, a.result, a.remarks, b.trace_no,a.code FROM lab_enumresult a LEFT JOIN so_header b ON a.so_no = b.so_no AND a.branch = b.branch LEFT JOIN patient_info c ON b.patient_id = c.patient_id WHERE a.so_no = '$_REQUEST[so_no]' AND `code` = '$_REQUEST[code]' AND serialno = '$_REQUEST[serialno]';");
	$b = $con->getArray("SELECT method,testkit,lotno,date_format(expiry, '%m/%d/%Y') as expiry,result,remarks FROM lab_enumresult WHERE so_no = '$_REQUEST[so_no]' AND `code` = '$_REQUEST[code]' and serialno = '$_REQUEST[serialno]';");


	if($_ihead['verified_by'] != '') {
        list($medtechSignature,$medtechFullname,$medtechLicense,$medtechRole) = $con->getArray("SELECT if(signature_file != '',concat('<img src=\"../images/signatures/',signature_file,'\" align=absmiddle />'),'<img src=\"../images/signatures/blank.png\" align=absmiddle />') as signature, fullname, license_no, role from user_info where emp_id = '$_ihead[verified_by]';");
    }
    

    list($procedure) = $con->getArray("SELECT `description` FROM services_master WHERE `code` = '$_REQUEST[code]';");

/* END OF SQL QUERIES */

$mpdf=new mPDF('win-1252','LETTER-H','','',10,10,65,30,5,5);
$mpdf->use_embeddedfonts_1252 = true;    // false is default
$mpdf->SetProtection(array('print'));
$mpdf->SetAuthor("PORT80 Solutions");

$mpdf->SetWatermarkText('FOR VALIDATION');
$mpdf->watermarkTextAlpha = 0.03;
$mpdf->showWatermarkText = true;


$mpdf->SetDisplayMode(50);

$html = '
<html>
<head>
	<style>
		body {font-family: sans-serif; font-size: 9px; }
		.itemHeader {
			padding:5px;border:1px solid black; text-align: center; font-weight: bold;
		}

		.itemResult {
			padding:15px;border:1px solid black;text-align: center;
		}

		#items td { border: 1px solid; text-align: center; }
	</style>
</head>
<body>

<!--mpdf
<htmlpageheader name="myheader">
<table width="100%" cellpadding=0 cellspaing=0>
	<tr><td align=center><img src="../images/doc-header.jpg" /></td></tr>

    <tr>
		<td width="100%" style="padding-top: 5px;" align=center>
			<span style="font-weight: bold; font-size: 12pt; color: #000000;">LABORATORY DEPARTMENT</span>
		</td>
	</tr>

</table>
<table width=100% cellpadding=2 cellspacing=0 style="font-size: 10pt;margin-top:5px;">
	<tr>
		<td width=100% colspan=4 style="background-color: #cdcdcd; border-top: 1px solid black; border-bottom: 1px solid black;" align=center><b>PATIENT INFORMATION</b></td>
	</tr>
	<tr>
		<td width=25%><b>CASE NO.</b></td>
		<td width=45%>:&nbsp;&nbsp;'.$_ihead['serialno'].'</td>
		<td width=15%><b>DATE</b></td>
		<td width=15%>:&nbsp;&nbsp;'.$_ihead['rdate'].'</td>
	</tr>
	<tr>
		<td><b>PATIENT NAME</b></td>
		<td>:&nbsp;&nbsp;'.$_ihead['patient_name'].'</td>
		<td><b>GENDER</b></td>
		<td>:&nbsp;&nbsp;'.$_ihead['gender'].'</td>
	</tr>
	<tr>
		<td><b>PATIENT ADDRESS</b></td>
		<td>:&nbsp;&nbsp;' . $_ihead['patient_address'] . '</td>
		<td><b>AGE</b></td>
		<td>:&nbsp;&nbsp;'.$_ihead['age'].'yo</td>
	</tr>
	<tr>
		<td><b>REQUESTING PHYSICIAN</b></td>
		<td>:&nbsp;&nbsp;'.$_ihead['physician'].'</td>
		<td></td>
		<td></td>
	</tr>
</table>

</htmlpageheader>

<htmlpagefooter name="myfooter">
<table width=100% cellpadding=5>
	<tr>
		<td align=center valign=top>'.$medtechSignature.'<br/><b>'.$medtechFullname.'<br/>___________________________________________<br>'.$medtechRole.'<br/>License No. '.$medtechLicense.'</b></td>
		<td align=center valign=top><img src="../images/signatures/psa-signature.png" align=absmidddle /><br/><b>PETER S. AZNAR, M.D, F.P.S.P<br/>___________________________________________<br><b>PATHOLOGIST</b><br><span style="font-size: 7pt;">PRC LICENSE NO. 72410</span></td>
	</tr>
    <tr><td align=left><barcode size=0.8 code="'.substr($_ihead['trace_no'],0,10).'" type="C128A"></td><td align=right>Date & Time Printed : '.date('m/d/Y h:i:s a').'</td></tr>
</table>
</htmlpagefooter>

<sethtmlpageheader name="myheader" value="on" show-this-page="1" />
<sethtmlpagefooter name="myfooter" value="on" />
mpdf-->
<div id="main">
    <table width=60% cellpadding=0 cellspacing=0 align=center style="margin: 5px;">
        <tr><td align=center><span style="font-size: 12pt; font-weight: bold;">'.$procedure.'</span></td></tr>
    </table>
    <table width=60% cellpadding=0 cellspacing=0 align=center style="border:1px solid black; padding: 10px;">
        <tr><td width=100% align=center><span style="font-size: 14pt; font-weight: bold; font-style: italic;">'.$_ihead['result'].'</span></td></tr>
    </table>
    <table width=60% align=center style="margin-top: 5px; font-size: 9pt; font-style: italic;">
        <tr>
            <td align=left width=18%><b>REMARKS :</b></td>';
		if($_ihead['code'] == 'L082') {
			$html .= '<td align=left width=82% style="border-bottom: 1px solid black;">Screening Test Only.</td>';
		} else { 
			$html .= '<td align=left width=82% style="border-bottom: 1px solid black;">'.$_ihead['remarks'].'</td>';
		}
        $html .= '</tr>
    </table>';
	if($_ihead['code'] == 'L082') {
	$html .='<table width=100% cellpadding=0 cellspacing=0 style="font-style: italic; margin-top: 5px; font-size: 7pt;">
		<tr><td width=80><b>Method :</b></td><td>'.$b['method'].'</td></tr>
		<tr><td width=80><b>Test Kit :</b></td><td>'.$b['testkit'].'</td></tr>
		<tr><td width=80><b>Lot No :</b></td><td>'.$b['lotno'].'</td></tr>
		<tr><td width=80><b>Expiry Date :</b></td><td>'.$b['expiry'].'</td></tr>';
	}
$html .=	'</table>
</div>
</body>
</html>
';

$html = html_entity_decode($html);
$mpdf->WriteHTML($html);
$mpdf->Output(); exit;
exit;

mysql_close($con);
?>