<?php
    // ini_set("display_errors","on");
    session_start();
	require_once "handlers/_generics.php";
	
	$o = new _init;
	if(isset($_SESSION['authkey'])) { $exception = $o->validateKey(); if($o->exception != 0) {	$URL = $HTTP_REFERER . "login.php?exception=" . $o->exception; } } else { $URL = $HTTP_REFERER . "login.php"; }
	if($URL) { header("Location: $URL"); };


    list($pname) = $o->getArray("select concat(lname, ', ',fname, ' ',mname, ' ',suffix) from patient_info where patient_id = '$_SESSION[pid]';");


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
	.dataTables_wrapper {
		display: inline-block;
	    font-size: 11px;
		width: 100%; 
	}
	
	table.dataTable tr.odd { background-color: #f5f5f5;  }
	table.dataTable tr.even { background-color: white; }
	.dataTables_filter input { width: 250px; }
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
        <div class="col-md-8 mb-4">
            <div class="table-bg mt-2 mb-4">
                <div class="header-group">
                    <h6 class="text-reset fw-bold">Welcome to <b>PRIME CARE ALPHA Results Page!</b></h6>
                    <p class="mt-4 mb-0 bg-orange"><b>NOTICE:</b> Only results <b>January 2021</b> onwards are available in this portal. Should you need assistance on results not found online, our <b>Medical Records</b> section will very much eager to help you. Please call our landline hotline at (32) 232-2273 Local 1111 or mobile no. +63927-955-5625. You may also send us an email at <b>medicalrecords@primecarealpha.ph</b>. Thank you.</p>
                </div>
                <div class="results-box mt-2">
                    <table class="cell-border" id="itemlist" style="font-size:11px;" width=100%>
                        <thead>
                            <tr>
                                <th></th>
                                <th width=15%>LAB NO.</th>
                                <th width=15%>ORDER NO.</th>
                                <th width=15%>ORDER DATE</th>
                                <th>PATIENT ID</th>
                                <th>PATIENT NAME</th>
                                <th>GENDER</th>
                                <th>AGE</th>
                                <th>PROCEDURE</th>
                                <th width=20%>TYPE</th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <div class="mt-2 mb-2 p-0">
                    <button type="button" class="dload-btn ui-button ui-widget ui-corner-all" onClick="downloadResult();">
                        <img src="img/icon/doc-search.png" width=25 height=25 align=absmiddle /> &nbsp;&nbsp;View Selected Result
                    </button>
                    <!--button type="button" class="dload-btn ui-button ui-widget ui-corner-all" onClick="printSO();">
                        <img src="img/icon/invoice.png" width=25 height=25 align=absmiddle /> &nbsp;&nbsp;View Selecte Sales Order
                    </button-->
                </div>
                <div class="results-footer p-3 mt-2">
                    <p style="float:left;">Please email us at support@primecarealpha.ph for any concerns. Thank you!</p>
                    <p><a href="policyprivacy.php" style="float: right;">Terms | Policy Privacy</a></p>
                </div>
            </div>
        </div>
  </section>

  <div style="background-color: rgb(240, 240, 240);" class="mt-4">
    <div class="col footer">
         <div class="text-center p-3">
             © 2023
         <a class="text-reset fw-bold" href="https://primecarealpha.ph/">Prime Care Alpha</a>
         </div>
    </div>
 <div id = "resultDiv" style="display: none;"></div>
 <div id = "soPrint" style="display: none;"></div>
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
    $(document).ready(function() {


        var myTable = $('#itemlist').DataTable({
            "keys": true,
            "scrollY":  "250px",
            "select":	'single',
            "pageLength": 50,
            "pagingType": "full_numbers",
            "bProcessing": true,
            "responsive": true,
            "sAjaxSource": "data/resultlist.php",
            "scroller": true,
            "order": [[ 1, "desc" ]],
            "bsort": false,
            "aoColumns": [
                { mData: 'id' } ,
                { mData: 'serialno' },
                { mData: 'order_no' },
                { mData: 'sodate' },
                { mData: 'pid' },
                { mData: 'pname' },
                { mData: 'gender' },
                { mData: 'age' },
                { mData: 'procedure' },
                { mData: 'subcategory' },
                { mData: 'code' },
                { mData: 'trace_no' }
            ],
            "aoColumnDefs": [
                { "className": "dt-body-center", "targets": [1,2,3]},
                { "targets": [0,4,5,6,7,10,11], "visible": false }
            ]
        });
    });

    function downloadResult() {
		var table = $("#itemlist").DataTable();		
	   	$.each(table.rows('.selected').data(), function() {
		    lid = this["id"];
			code = this['code'];
			sono = this['order_no'];
			serialno = this['serialno'];
	   	});

		if(lid) {
		    printResult(code,sono,serialno);
		} else {
			alert("Please select result to view!");
		}

	}

    function printResult(code,so_no,serialno) {
        let xCode = code.substring(0,1);
        if(xCode == 'X') {
            var txtHTML = "<iframe id='printResult' frameborder=0 width='100%' height='100%' src='print/result.xray.php?so_no="+so_no+"&code="+code+"&serialno="+serialno+"&sid="+Math.random()+"&sid="+Math.random()+"'></iframe>";
        } else {
            switch(code) {

                case "L010":
                case "L204":
                case "L210":
                    var txtHTML = "<iframe id='printResult' frameborder=0 width='100%' height='100%' src='print/result.cbc.php?so_no="+so_no+"&code="+code+"&serialno="+serialno+"&sid="+Math.random()+"&sid="+Math.random()+"'></iframe>";
                break;
                case "L011":
                    var txtHTML = "<iframe id='printResult' frameborder=0 width='100%' height='100%' src='print/result.bloodchem.php?so_no="+so_no+"&code="+code+"&serialno="+serialno+"&sid="+Math.random()+"&sid="+Math.random()+"'></iframe>";
                break;
                case "L012":
                case "L205":
                case "O153":
                    var txtHTML = "<iframe id='printResult' frameborder=0 width='100%' height='100%' src='print/result.ua.php?so_no="+so_no+"&code="+code+"&serialno="+serialno+"&sid="+Math.random()+"&sid="+Math.random()+"'></iframe>";
                break;
                case "L013":
                case "L211":
                    var txtHTML = "<iframe id='printResult' frameborder=0 width='100%' height='100%' src='print/result.stool.php?so_no="+so_no+"&code="+code+"&serialno="+serialno+"&sid="+Math.random()+"&sid="+Math.random()+"'></iframe>";
                break;
                case "L014":
                    semenAnalysis(lid,code);
                break;
                case "L046":	
                case "L084":	
                case "L043":
                case "L039":
                case "L136":	
                case "L007":
                case "L071":
                case "L086":
                case "L100":
                case "L132":
                case "L041":
                case "L146":
                case "L033":
                case "L034":	
                case "L101":
                case "L062":
                case "L206":
                    var txtHTML = "<iframe id='printResult' frameborder=0 width='100%' height='100%' src='print/result.enum.php?so_no="+so_no+"&code="+code+"&serialno="+serialno+"&sid="+Math.random()+"&sid="+Math.random()+"'></iframe>";
                break;
                case "L087":
                case "L015":
                case "L096":
                case "L193":
                case "L170":
                    var txtHTML = "<iframe id='printResult' frameborder=0 width='100%' height='100%' src='print/result.antigen.php?so_no="+so_no+"&code="+code+"&serialno="+serialno+"&sid="+Math.random()+"&sid="+Math.random()+"'></iframe>";
                break;
                // case "L041":
                // 	var txtHTML = "<iframe id='printResult' frameborder=0 width='100%' height='100%' src='print/result.reactives.php?so_no="+so_no+"&code="+code+"&serialno="+serialno+"&sid="+Math.random()+"&sid="+Math.random()+"'></iframe>";
                // break;

                case "L063":
                case "L064":
                    var txtHTML = "<iframe id='printResult' frameborder=0 width='100%' height='100%' src='print/result.pt.php?so_no="+so_no+"&code="+code+"&serialno="+serialno+"&sid="+Math.random()+"&sid="+Math.random()+"'></iframe>";
                break;
                case "L052":
                case "L053":
                    var txtHTML = "<iframe id='printResult' frameborder=0 width='100%' height='100%' src='print/result.bt.php?so_no="+so_no+"&code="+code+"&serialno="+serialno+"&sid="+Math.random()+"&sid="+Math.random()+"'></iframe>";
                break;
                case "L079":
                case "L147":
                    var txtHTML = "<iframe id='printResult' frameborder=0 width='100%' height='100%' src='print/result.dengue.php?so_no="+so_no+"&code="+code+"&serialno="+serialno+"&sid="+Math.random()+"&sid="+Math.random()+"'></iframe>";
                break;
                case "L051":
                case "L056":
                    var txtHTML = "<iframe id='printResult' frameborder=0 width='100%' height='100%' src='print/result.bleedclot.php?so_no="+so_no+"&code="+code+"&serialno="+serialno+"&sid="+Math.random()+"&sid="+Math.random()+"'></iframe>";
                break;
                case "L082":
                    var txtHTML = "<iframe id='printResult' frameborder=0 width='100%' height='100%' src='print/result.hiv.php?so_no="+so_no+"&code="+code+"&serialno="+serialno+"&sid="+Math.random()+"&sid="+Math.random()+"'></iframe>";
                break;
                case "O001":
                case "O156":
                    var txtHTML = "<iframe id='printResult' frameborder=0 width='100%' height='100%' src='print/result.ecg.php?so_no="+so_no+"&code="+code+"&serialno="+serialno+"&sid="+Math.random()+"&sid="+Math.random()+"'></iframe>";
                break;
                default:
                    var txtHTML = "<iframe id='printResult' frameborder=0 width='100%' height='100%' src='print/result.singlevalue.php?so_no="+so_no+"&code="+code+"&serialno="+serialno+"&sid="+Math.random()+"&sid="+Math.random()+"'></iframe>";
                break;
            }
        }
        
        $("#resultDiv").html(txtHTML);
        $("#resultDiv").dialog({title: "View Result", width: 720, height: 800, resizable: true });

    }

    function printSO() {
        var table = $("#itemlist").DataTable();		
	   	$.each(table.rows('.selected').data(), function() {
			traceno = this['trace_no'];
	   	});

        if(traceno) {
            var txtHTML = "<iframe id='frmcust' frameborder=0 width='100%' height='100%' src='print/so.print.php?traceno="+traceno+"&sid="+Math.random()+"'></iframe>";
            $("#soPrint").html(txtHTML);
            $("#soPrint").dialog({title: "PRINT >> SERVICE ORDER", width: 560, height: 620, resizable: true }).dialogExtend({
                "closable" : true,
                "maximizable" : true,
                "minimizable" : true
            });
        } else {
            alert("Please select record to print!");
        }


    }

    function logout() {
        if(confirm("Are you sure you want to logout from this portal?") == true) {
            $.post("logout.php",function() {
                location.href = "login.php";
            });

        }

    }

  </script>
</body>

</html>