<?php
    // ini_set("display_errors","on");
    session_start();
	require_once "handlers/_generics.php";
	
	$o = new _init;
	if(isset($_SESSION['authkey'])) { $exception = $o->validateKey(); if($o->exception != 0) {	$URL = $HTTP_REFERER . "login.php?exception=" . $o->exception; } } else { $URL = $HTTP_REFERER . "login.php"; }
	if($URL) { header("Location: $URL"); };


    list($pname) = $o->getArray("select concat(lname, ', ',fname, ' ',mname, ' ',suffix) from patient_info where patient_id = '$_SESSION[pid]';");
    list($pid,$birthdate,$email) = $o->getArray("select patient_id,birthdate,email_add from patient_info where patient_id = '$_SESSION[pid]';");

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
            <div class="table-bg mt-2 mb-4">
                <div class="header-group">
                    <h6 class="text-reset fw-bold"><b>Privacy Statement</b></h6>
                    <p class="mt-4 mb-0 par">Prime Care Alpha ("we," "us," or "our") is committed to protecting your privacy and ensuring the security of your personal information. This Privacy Statement Policy outlines how we collect, use, disclose, and safeguard your personal information when you interact with our website and services. By accessing or using our services, you consent to the practices described in this policy.</p>
                    <p class="par"><b class="title">Information We Collect</b><br />We may collect the following types of personal information from you:</p>
                    <p class="para">1. Contact Information: This includes your name, email address, phone number, and postal address when you sign up for our services, request information, or communicate with us.</p>
                    <p class="para">2. Health Information: In certain instances, we may collect health-related information to provide you with personalized healthcare services. This information will be collected with your explicit consent.</p>
                    <p class="para">3. Account Information: When you create an account with us, we may collect information such as usernames, passwords, and other credentials necessary to access your account.</p>
                    <p class="para">4. Payment Information: If you make payments for our services, we may collect payment card information or other financial information required to process your payment.</p>
                    <p class="para">5. Usage Information: We may collect information about how you interact with our website and services, including your IP address, browser type, operating system, device information, and usage patterns.</p>
                    <p class="para">6. Communications: We may collect and store any communications between you and us, including emails, chats, and customer support inquiries.</p>
                    <p class="para">7. Cookies and Tracking Technologies: We use cookies and similar tracking technologies to collect information about your browsing behavior on our website. You can manage your cookie preferences through your browser settings.</p>
                    
                    <p class="par"><b class="title">How We Use Your Information</b><br />We may use your personal information for the following purposes:</p>
                    <p class="para">1. Provide Healthcare Services: To deliver personalized healthcare services and support, including appointment scheduling, medical records management, and telehealth consultations.</p>
                    <p class="para">2. Personalization: To customize and improve your healthcare experience with us and tailor our services to your medical needs and preferences.</p>
                    <p class="para">3. Communication: To communicate with you about your healthcare, appointment reminders, service updates, and respond to your inquiries.</p>
                    <p class="para">4. Analytics: To analyze and understand how our website and services are used, improve our healthcare offerings, and measure the effectiveness of our medical advice and treatment plans.</p>
                    <p class="para">5. Security: To protect the security and confidentiality of your health information and detect and prevent fraudulent activities.</p>
                    <p class="para">6. Legal Compliance: To comply with healthcare regulations and legal obligations related to patient records and confidentiality.</p>

                    <p class="par"><b class="title">Disclosure of Your Information</b><br />We may share your personal information with:</p>
                    <p class="para">1. Healthcare Providers: Our affiliated healthcare providers and professionals involved in your medical care.</p>
                    <p class="para">2. Service Providers: Third-party service providers who assist us in providing our healthcare services, such as appointment scheduling, payment processing, and telehealth technology providers.</p>
                    <p class="para">3. Legal Requirements: When required by healthcare laws and regulations, we may disclose your health information to comply with legal processes, government requests, or protect our rights and your health.</p>

                    <p class="par"><b class="title">Your Rights and Choices</b><br />You have certain rights regarding your personal and health information, including:</p>
                    <p class="para">1. Access: You can request access to your health and personal information held by us.</p>
                    <p class="para">2. Correction: You can request corrections to inaccuracies in your health information.</p>
                    <p class="para">3. Deletion: You can request the deletion of your health information in certain circumstances, subject to legal requirements.</p>
                    <p class="para">4. Opt-Out: You can opt out of certain communications or withdraw your consent at any time, subject to legal limitations.</p>
                    
                    <p class="par"><b class="title">Security</b></p>
                    <p class="para">We implement rigorous security measures to protect your personal and health information. However, please be aware that no data transmission over the internet is entirely secure, and we cannot guarantee the absolute security of your information.</p>

                    <p class="par"><b class="title">Changes to This Policy</b></p>
                    <p class="para">We may update this Privacy Statement Policy periodically to reflect changes in our practices or for legal or regulatory reasons. We will notify you of any material changes via email or a notice on our website.</p>

                    <p class="par"><b class="title">Contact Us</b></p>
                    <p class="para">If you have any questions or concerns about our privacy practices or this policy, please contact our Privacy Officer at [Insert Contact Information].</p>
                    <p class="para">By using our healthcare services, you acknowledge that you have read and understood this Privacy Statement Policy and consent to the collection, use, and disclosure of your personal and health information as described herein.</p>

                    <p class="par"><b class="title-1">DATA PRIVACY OFFICER<br>Prime Care Alpha by Medgruppe Polyclinics and Diagnostic Center Inc.<br/>APM Centrale, A. Soriano Ave. Mabolo, Cebu City, 6000</b></p>
                </div>
                <div class="mt-2 p-0">
                    <button type="button" class="dload-btn ui-button ui-widget ui-corner-all" onClick="backtomain();">
                        <img src="img/icon/previous.png" width=25 height=25 align=absmiddle /> &nbsp;&nbsp;Back to Main Menu
                    </button>
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