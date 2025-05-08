<?php
    session_start();
    require_once("handlers/initDB.php");
    $con = new myDB();
    
    /* delete active sessions */
    $con->dbquery("delete from online_active_sessions where userid = '$_SESSION[pid]';");
    session_destroy();


?>