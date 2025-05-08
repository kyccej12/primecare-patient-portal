<?php
    // ini_set("display_errors","on");
    session_start();
	require_once "handlers/_generics.php";
	
	$o = new _init;
	if(isset($_SESSION['authkey'])) { $exception = $o->validateKey(); if($o->exception != 0) {	$URL = $HTTP_REFERER . "login.php?exception=" . $o->exception; } } else { $URL = $HTTP_REFERER . "login.php"; }
	if($URL) { header("Location: $URL"); };


    list($pname) = $o->getArray("select concat(lname, ', ',fname, ' ',mname, ' ',suffix) from patient_info where patient_id = '$_SESSION[pid]';");
    list($pid,$birthdate,$email) = $o->getArray("select patient_id,date_format(birthdate,'%m/%d/%Y'),email_add from patient_info where patient_id = '$_SESSION[pid]';");
    $a = $o->getArray("select *, DATE_FORMAT(birthdate,'%m/%d/%Y') AS bday, IF(gender='M','Male','Female') AS gender from patient_info where patient_id = '$_SESSION[pid]';");

    $age = $o->calculateAge($a['birthdate']);


?>



<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Prime Care Alpha - Main page</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="./assets/img/favicon.ico" rel="icon">
  <link href="./assets/img/apple-touch-icon.png" rel="apple-touch-icon">
  
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

  <!-- Bootstrap CDN -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Datatables -->
  <link href="ui-assets/themes/smoothness/jquery-ui.css" rel="stylesheet" type="text/css" />
  <link rel="stylesheet" type="text/css" href="ui-assets/datatables/css/jquery.dataTables.css">

  <!-- Template Main CSS File -->
  <link href="css/style.css" rel="stylesheet">

  <style>
    .footer {
        margin-top: 180px
    }
    </style>

</head>

<body class=" bg-wallpaper">
	<header class="header trans_200">
		<div class="logo_container_outer">
			<div class="container">
				<div class="row">
                    <div class="logo_container">
                        <div class="top">
                            <div class="col-md-10">
                                <div class="row">
                                    <div class="col-md-4 mt-2">
                                        <div class="left-align">
                                            <img src="img/pcc-logo.png">
                                        </div>
                                    </div>
                                    <div class="col-md-4 mt-2">
                                        <div class="right-align">
                                            <div class="col-md-3">
                                            <p><?php echo "[" . $_SESSION['pid'] . "] " . $pname; ?></p>
                                            </div>
                                            <div class="col-md-3">
                                            <a href="javascript: logout();">(Log-out)</a> |<a href="patientsettings.php"> My Account</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
				</div>
			</div>	
		</div>
	</header>
    <section id="main" class="main d-flex justify-content-center align-items-center">
        <div class="col-md-8">
            <div class="table-bg mt-2">
                <div class="header-group">
                    <h6 class="text-reset fw-bold">Welcome to <b>PRIME CARE ALPHA Results Page!</b></h6>
                    <p class="mt-4 mb-0 bg-orange"><b>NOTICE:</b> Only results <b>January 2021</b> onwards are available in this portal. There may be results that are not yet available on this portal. Should you need assistance on results not found online, our <b>Medical Records</b> section will very much eager to help you. Please call our hotline at (32) 123-456 or email us at <b>medicalrecords@primecarealpha.ph</b>. Thank you.</p>
                </div>
                <div class="results-box mt-2">
                <form action="" method="post">
                        <div class="row">
                            <div class="col-md-10 offset-md-1 form-1-box wow fadeInUp">
                                <!-- My Account -->
                                <table width="100%" border="0" cellspacing="0" cellpadding="0" >
                                    <td width="40%" align=center class="spandix-l" rowspan=9>
                                        <fieldset class="border p-3">
                                            <legend class="w-auto px-2 led">My Account</legend>
                                            <table width=100% style="font-size: 12px;">
                                                <tr>
                                                    <td width=30% class="spandix-l cab">Patient ID :</td>
                                                    <td><input type="text" name="pid" id="pid" class="gridInput" style="width: 90%; border:none;" value="<?php echo $pid; ?>" readonly></td>
                                                </tr>
                                                <tr>
                                                    <td width=30% class="spandix-l cab">Patient Name :</td>
                                                    <td><input type="text" name="pname" id="pname" class="gridInput" style="width: 90%; border:none;" value="<?php echo $pname; ?>" readonly></td>
                                                </tr>
                                                <tr>
                                                    <td width=30% class="spandix-l cab">Birthdate :</td>
                                                    <td><input type="text" name="bday" id="bday" class="gridInput" style="width: 90%; border:none;" value="<?php echo $birthdate ?>" readonly></td>
                                                </tr>
                                                <tr>
                                                    <td width=30% class="spandix-l cab">Gender :</td>
                                                    <td><input type="text" name="bday" id="bday" class="gridInput" style="width: 90%; border:none;" value="<?php echo $a['gender'] ?>" readonly></td>
                                                </tr>
                                                <tr>
                                                    <td width=30% class="spandix-l cab">Age :</td>
                                                    <td><input type="text" name="bday" id="bday" class="gridInput" style="width: 90%; border:none;" value="<?php echo $age . 'y/o'?>" readonly></td>
                                                </tr>
                                                <tr>
                                                    <td width=30% class="spandix-l cab">Address :</td>
                                                    <td><input type="text" name="bday" id="bday" class="gridInput" style="width: 90%; border:none;" value="<?php echo $a['patient_address'] ?>" readonly></td>
                                                </tr>
                                                <tr>
                                                    <td width=30% class="spandix-l cab">Contact No. :</td>
                                                    <td><input type="text" name="bday" id="bday" class="gridInput" style="width: 90%; border:none;" value="<?php echo $a['mobile_no'] ?>" readonly></td>
                                                </tr>
                                            </table>
                                        </fieldset>
                                    </td>		
                                </table>
                                <table width="100%" border="0" cellspacing="0" cellpadding="0" >
                                    <td width="40%" align=center class="spandix-l" rowspan=9>
                                        <fieldset class="border p-3">
                                            <legend class="w-auto px-2 led">Account Recovery</legend>
                                            <table width=100% style="font-size:12px;">
                                                <tr>
                                                    <td width=30% class="spandix-l cab">Registered Email :</td>
                                                    <td><input type="text" name="email" id="email" class="gridInput" style="width: 90%; border:none;" value="<?php echo $email ?>"></td>
                                                </tr>
                                            </table>
                                        </fieldset>
                                    </td>		
                                </table>
                                <table width="100%" border="0" cellspacing="0" cellpadding="0" >
                                    <td width="40%" align=center class="spandix-l" rowspan=9>
                                        <div class="form-group row">
                                            <div class="col text-left mt-2">
                                                <button type="button" class="dload-btn ui-button ui-widget ui-corner-all" onClick="backtomain();">
                                                    <img src="img/icon/previous.png" width=25 height=25 align=absmiddle /> &nbsp;&nbsp;Back to Main Menu
                                                </button>
                                            </div>
                                        </div>
                                    </td>		
                                </table>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="results-footer p-3 mt-2">
                    <p style="float:left;">Please email us at support@primecarealpha.ph for any concerns. Thank you!</p>
                    <p><a href="policyprivacy.php" style="float: right;">Terms | Policy Privacy</a></p>
                </div>
            </div>
        </div>
  </section>

  <div style="background-color: rgb(240, 240, 240);" class="btm">
    <div class="col footer">
         <div class="text-center p-3">
             © 2023
         <a class="text-reset fw-bold" href="https://primecarealpha.ph/">Prime Care Alpha</a>
         </div>
    </div>
</div>
  
  <!-- JavaScript Libraries -->
  <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>
  <script type="text/javascript" charset="utf8" src="ui-assets/jquery/jquery-1.12.3.js"></script>
  <script type="text/javascript" charset="utf8" src="ui-assets/themes/smoothness/jquery-ui.js"></script>
  <script type="text/javascript" charset="utf8" src="ui-assets/themes/smoothness/jquery-ui.js"></script>
  <script type="text/javascript" charset="utf8" src="ui-assets/datatables/js/jquery.dataTables.js"></script>
  <script type="text/javascript" charset="utf8" src="ui-assets/datatables/js/dataTables.jqueryui.js"></script>
  <script type="text/javascript" charset="utf8" src="ui-assets/datatables/js/dataTables.select.js"></script>
  <script type="text/javascript" charset="utf8" src="ui-assets/keytable/js/dataTables.keyTable.min.js"></script>

  <script>
        function logout() {
        if(confirm("Are you sure you want to logout from this portal?") == true) {
            $.post("logout.php",function() {
                location.href = "login.php";
            });

        }

    }

    function backtomain() {
            window.location.href ="index.php";

        }
 </script>
</body>

</html>