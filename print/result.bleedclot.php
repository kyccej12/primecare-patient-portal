<?php
	session_start();
	include("../lib/mpdf6/mpdf.php");
	include("../handlers/_generics.php");

	$con = new _init;

/* MYSQL QUERIES SECTION */
	$now = date("m/d/Y h:i a");
	$co = $con->getArray("select * from companies where company_id = '$_SESSION[company]';");

	$_ihead = $con->getArray("SELECT DATE_FORMAT(result_date,'%m/%d/%Y') AS rdate, b.patient_name, b.patient_address, IF(c.gender='M','Male','Female') AS gender, c.gender AS xgender, c.birthdate, b.physician, a.serialno, a.created_by, b.trace_no, a.code FROM lab_bleed_clot a LEFT JOIN so_header b ON a.so_no = b.so_no AND a.branch = b.branch LEFT JOIN patient_info c ON b.patient_id = c.patient_id WHERE a.so_no = '$_REQUEST[so_no]' AND `code` = '$_REQUEST[code]' AND serialno = '$_REQUEST[serialno]';");
    $b = $con->getArray("SELECT bleed_min, bleed_sec, clot_min,clot_sec, verified, verified_by FROM lab_bleed_clot WHERE so_no = '$_REQUEST[so_no]' and code = '$_REQUEST[code]' and serialno = '$_REQUEST[serialno]';");	
	
	if($b['verified_by'] != '') {
        list($medtechSignature,$medtechFullname,$medtechLicense,$medtechRole) = $con->getArray("SELECT if(signature_file != '',concat('<img src=\"../images/signatures/',signature_file,'\" align=absmiddle />'),'<img src=\"../images/signatures/blank.png\" align=absmiddle />') as signature, fullname, license_no, role from user_info where emp_id = '$b[verified_by]';");
    }


/* END OF SQL QUERIES */

$mpdf=new mPDF('win-1252','FOLIO-H','','',10,10,68,30,5,5);
$mpdf->use_embeddedfonts_1252 = true;    // false is default
$mpdf->SetProtection(array('print'));
$mpdf->SetAuthor("PORT80 Solutions");
$mpdf->SetWatermarkText('ONLINE COPY');
$mpdf->showWatermarkText = true;
$mpdf->SetDisplayMode(50);

$html = '
<html>
<head>
	<style>
		body {font-family: sans-serif; font-size: 10px; }
        .itemHeader {
            padding:10px;border:1px solid black; text-align: center; font-weight: bold;
        }

        .itemResult {
            padding:20px;border:1px solid black;text-align: center;
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
			<td>:&nbsp;&nbsp;'.$con->calculateAge($_ihead['birthdate']).'</td>
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
    <tr><td align=left><barcode size=0.8 code="'.substr($_ihead['trace_no'],0,25).'" type="C128A"></td><td align=right>Date & Time Printed : '.date('m/d/Y h:i:s a').'</td></tr>
</table>
</htmlpagefooter>

<sethtmlpageheader name="myheader" value="on" show-this-page="1" />
<sethtmlpagefooter name="myfooter" value="on" />
mpdf-->
<div id="main">
	<table width=60% cellpadding=0 cellspacing=0 align=center style="margin: 5px;">
        <tr><td align=center><span style="font-size: 12pt; font-weight: bold;">BLEEDING TIME & CLOTTING TIME</span></td></tr>
    </table>

	<table width=80% cellpadding=0 cellspacing=0 align=center style="border-collapse: collapse; font-size: 12px;">
            <tr>
                <td class="itemHeader">TEST</td>
                <td class="itemHeader">RESULT</td>
                <td class="itemHeader">NORMAL VALUES</td>
            </tr>
            <tr>
                <td class="itemResult">BLEEDING TIME</td>
                <td class="itemResult">'.$b['bleed_min'].'&nbsp;MINUTES&nbsp;AND&nbsp;'.$b['bleed_sec'].'&nbsp;SECONDS</td>
                <td class="itemResult">(Normal range: 2 - 7 mins.)</td>
            </tr>
            <tr>
                <td class="itemResult">CLOTTING TIME</td>
                <td class="itemResult">'.$b['clot_min'].'&nbsp;MINUTES&nbsp;AND&nbsp;'.$b['clot_sec'].'&nbsp;SECONDS</td>
                <td class="itemResult">(Normal range: 8 - 15 mins.)</td>
            </tr>

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