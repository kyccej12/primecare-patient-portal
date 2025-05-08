<?php
	session_start();
	include("../lib/mpdf6/mpdf.php");
	include("../handlers/_generics.php");

	$con = new _init;

/* MYSQL QUERIES SECTION */
	$now = date("m/d/Y h:i a");
	$co = $con->getArray("select * from companies where company_id = '$_SESSION[company]';");

	$_ihead = $con->getArray("SELECT DATE_FORMAT(result_date,'%m/%d/%Y') AS rdate, b.patient_name, b.patient_address, c.gender as xgender, IF(c.gender='M','Male','Female') AS gender, c.gender as xgender, c.birthdate, b.physician, a.serialno,a.created_by,b.trace_no,c.employer as company FROM lab_dengue a LEFT JOIN so_header b ON a.so_no = b.so_no AND a.branch = b.branch LEFT JOIN patient_info c ON b.patient_id = c.patient_id WHERE a.so_no = '$_REQUEST[so_no]' and a.serialno = '$_REQUEST[serialno]';");  
    $b = $con->getArray("SELECT method,testkit,lotno,date_format(expiry, '%m/%d/%Y') as expiry,dengue_ag,dengue_igg,dengue_igm,remarks,verified_by,verified FROM lab_dengue WHERE so_no = '$_REQUEST[so_no]' and serialno = '$_REQUEST[serialno]';");
	
	if($b['verified_by'] != '') {
        list($medtechSignature,$medtechFullname,$medtechLicense,$medtechRole) = $con->getArray("SELECT if(signature_file != '',concat('<img src=\"../images/signatures/',signature_file,'\" align=absmiddle />'),'<img src=\"../images/signatures/blank.png\" align=absmiddle />') as signature, fullname, license_no, role from user_info where emp_id = '$b[verified_by]';");
    }		

/* END OF SQL QUERIES */

$mpdf=new mPDF('win-1252','LETTER','','',15,10,82,30,5,5);
$mpdf->use_embeddedfonts_1252 = true;    // false is default
$mpdf->SetProtection(array('print'));
$mpdf->SetAuthor("PORT80 Solutions");

$mpdf->SetWatermarkText('ONLINE COPY');
$mpdf->watermarkTextAlpha = 0.03;
$mpdf->showWatermarkText = true;


$mpdf->SetDisplayMode(50);

$html = '
<html>
<head>
	<style>
	body {font-family: sans-serif; font-size: 10px; }
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
		<td width="100%" style="padding-top: 30px;" align=center>
			<span style="font-weight: bold; font-size: 12pt; color: #000000;">LABORATORY DEPARTMENT</span>
		</td>
	</tr>

</table>
<table width=100% cellpadding=2 cellspacing=0 style="font-size: 9pt;margin-top:10px;">
	<tr>
		<td width=100% colspan=4 style="background-color: #cdcdcd; border-top: 1px solid black; border-bottom: 1px solid black;" align=center><b>PATIENT INFORMATION</b></td>
	</tr>
	<tr>
		<td width=25%><b>CASE NO.</b></td>
		<td width=40%>:&nbsp;&nbsp;'.$_ihead['serialno'].'</td>
		<td width=20%><b>DATE</b></td>
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
		<td>:&nbsp;&nbsp;'.$con->calculateAge($_ihead['birthdate']).'yo</td>
	</tr>
	<tr>
		<td><b>COMPANY NAME</b></td>
		<td>:&nbsp;&nbsp;'.$_ihead['company'].'</td>
		<td><b>EXAMINATION</b></td>
		<td>:&nbsp;&nbsp;DENGUE&nbsp;DUO</td>
	</tr>
</table>

</htmlpageheader>

<htmlpagefooter name="myfooter">
<table width=100% cellpadding=5 style="margin-bottom: 25px;">
	<tr>
		<td align=center valign=top>'.$medtechSignature.'<br/><b>'.$medtechFullname.'<br/>___________________________________________<br>'.$medtechRole.'<br/>License No. '.$medtechLicense.'</b></td>
		<td align=center valign=top><img src="../images/signatures/psa-signature.png" align=absmidddle /><br/><b>PETER S. AZNAR, M.D, F.P.S.P<br/>___________________________________________<br><b>PATHOLOGIST</b><br><span style="font-size: 7pt;">PRC LICENSE NO. 72410</span></td>
	</tr>
</table>
<table width=100%>
	<tr><td align=left><barcode size=0.8 code="'.substr($_ihead['trace_no'],0,10).'" type="C128A"></td><td align=right>Run Date: '.date('m/d/Y h:i:s a').'</td></tr>
</table>
</htmlpagefooter>

<sethtmlpageheader name="myheader" value="on" show-this-page="1" />
<sethtmlpagefooter name="myfooter" value="on" />
mpdf-->
<div id="main">
    <table width=80% cellpadding=0 cellspacing=0 align=center style="border-collapse: collapse; font-size: 12px;">

            <tr>
                <td class="itemHeader">DENGUE&nbsp;NS1&nbsp;AG</td>
                <td class="itemHeader">DENGUE&nbsp;IgG</td>
				<td class="itemHeader">DENGUE&nbsp;IgM</td>

            </tr>
            <tr>
                <td class="itemResult">'.$b['dengue_ag'].'</td>
				<td class="itemResult">'.$b['dengue_igg'].'</td>
				<td class="itemResult">'.$b['dengue_igm'].'</td>
            </tr>
    </table>

	<table width=60% align=center style="margin-top: 5px; font-size: 9pt; font-style: italic;">
		<tr>
			<td align=left width=18%><b>REMARKS :</b></td>
			<td align=left width=82% style="border-bottom: 1px solid black;">'.$b['remarks'].'</td>
		</tr>
	</table>
	<table width=100% cellpadding=0 cellspacing=0 style="font-style: italic; margin-top: 15px; font-size: 7pt;">
		<tr><td width=80><b>Method :</b></td><td>'.$b['method'].'</td></tr>
		<tr><td width=80><b>Test Kit :</b></td><td>'.$b['testkit'].'</td></tr>
		<tr><td width=80><b>Lot No :</b></td><td>'.$b['lotno'].'</td></tr>
		<tr><td width=80><b>Expiry Date :</b></td><td>'.$b['expiry'].'</td></tr>
	</table>
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