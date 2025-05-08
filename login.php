<?php
    function _returnError($code) {
        switch($code) {
            case "1": echo "Unable to log you in. You may have specified an invalid username or password!"; break;
            case "2": echo "You have been logged out as your session has already expired!"; break;
            case "3": echo "Unable to renew Session ID. Please contact system administrator to correct this problem."; break;
            case "4": echo "Unable to retrieve Session Data. Try to login into the system again."; break;
        }
    }

?>


<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>Prime Care Alpha - Patient Login Page</title>

	<!-- Google font -->
	<link href="https://fonts.googleapis.com/css?family=Lato:400,700" rel="stylesheet">

	<!-- Bootstrap -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

	<!-- Custom stlylesheet -->
	<link type="text/css" rel="stylesheet" href="css/style.css" />

</head>

<body background="./img/background.jpg" style="background-size: cover; background-repeat: no-repeat;">
	<div id="login" class="section">
		<div class="section-center">
			<div class="container">
				<div class="row align-items-center" style="height: 100vh;">
					<div class="col-md-6">
						<div class="login-cta">
						</div>
					</div>
					<div class="col-md-8 mx-auto col-10 col-md-8 col-lg-6">
						<div class="text-center">
							<img class="top-img" src="./img/pcc-logo.png">
						</div>
						<div class="login-form mt-4">
								<div class="text-align-center justify-content-center mb-4">
									<h2>Login to your account</h2>
								</div>
							<form id="login">
								<div class="row">
									<div class="col-md-12">
										<div class="form-group mt-2">
											<span class="form-label">Username: *</span>
											<input name = "uname" id = "uname" class="form-control" type="text" name="uname" id="uname" placeholder="Input your username">
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12 mt-2">
										<div class="form-group">
											<span class="form-label">Password: *</span>
											<input name="password" id="password" class="form-control" type="password" placeholder="Input your password">
										</div>
									</div>
								</div>
								<!-- <div class="row">
									<div class="col-md-4 mb-4">
										<div class="forgot-password">
											<a href="#" data-toggle="modal" data-target="#forgotPass">Forgot your password?</a>
										</div>
									</div>
								</div> -->
		
								<div class="form-btn" id="login-submit">
									<button type="button" class="submit-btn g-recaptcha" data-sitekey="6Lf8GoEoAAAAADkTQw9-C83oNcZTIUkHn1bKdpNV" data-callback='onSubmit' data-action='submit' onclick="javascript: authenticate();">LOGIN</button>
								</div>
							</form>
							<!--div class="row">
								<div class="col-md-10 mt-4 reminder">
									<p><img src="./img/icon/reminder.png">&nbsp;&nbsp;You may refer to the instructions below for your login details.</p>
								</div>
							</div-->
							<div class="row">
								<div class="col mt-2">
									<p class="reminder-text">Your initial username is your Last Name Initial + Patient ID No. found in your Service Order Form (under patient's information) and password is your Birthdate (yyyy-mm-dd) format.</p>
								</div>
							</div>
							<div class="row">
								<div class="col mt-2">
									<p class="reminder-text">Ex.&nbsp;&nbsp;Patient Name: <b>Juan Dela Cruz</b>&nbsp;&nbsp;Patient ID No.: &nbsp;&nbsp;<b>012345</b><br/><br/>USERNAME: <b>D012345</b>&nbsp;&nbsp;Password: <b>1992-03-14</b></p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
<script src="https://www.google.com/recaptcha/api.js"></script>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script language="javascript" src="ui-assets/jquery/jquery-1.12.3.js"></script>
<script>
    function authenticate() {
        if($("#uname").val() != '' && $("#password").val() != '') {
            $.post("authenticate.php", { uname: $("#uname").val(), pass: $("#password").val() }, function(e) {
                location.href = e
            },"html");
        } else {

            alert("Unable to contine. Username and Password must be specified in order to login into the PCA's Results Portal System!")
        }
    }

	function onSubmit(token) {
     document.getElementById("login").submit();
   }

</script>
</body>

</html>