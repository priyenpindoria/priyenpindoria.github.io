<?php require_once('../Connections/connMISDS.php'); ?>
<?php
// Load the common classes
require_once('../includes/common/KT_common.php');

// Load the tNG classes
require_once('../includes/tng/tNG.inc.php');
	
// Make a transaction dispatcher instance
$tNGs = new tNG_dispatcher("../");

// Make unified connection variable
$conn_connMISDS = new KT_connection($connMISDS, $database_connMISDS);

//Start Restrict Access To Page
$restrict = new tNG_RestrictAccess($conn_connMISDS, "../");
//Grand Levels: Level
$restrict->addLevel("1");
$restrict->Execute();
//End Restrict Access To Page

if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

mysql_select_db($database_connMISDS, $connMISDS);
$query_user = "SELECT * FROM login_admin";
$user = mysql_query($query_user, $connMISDS) or die(mysql_error());
$row_user = mysql_fetch_assoc($user);
$totalRows_user = mysql_num_rows($user);

// Make a logout transaction instance
$logoutTransaction = new tNG_logoutTransaction($conn_connMISDS);
$tNGs->addTransaction($logoutTransaction);
// Register triggers
$logoutTransaction->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "GET", "KT_logout_now");
$logoutTransaction->registerTrigger("END", "Trigger_Default_Redirect", 99, "../login.php");
// Add columns
// End of logout transaction instance

// Make a logout transaction instance
$logoutTransaction1 = new tNG_logoutTransaction($conn_connMISDS);
$tNGs->addTransaction($logoutTransaction1);
// Register triggers
$logoutTransaction1->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "GET", "KT_logout_now");
$logoutTransaction1->registerTrigger("END", "Trigger_Default_Redirect", 99, "../login.php");
// Add columns
// End of logout transaction instance

// Execute all the registered transactions
$tNGs->executeTransactions();

// Get the transaction recordset
$rscustom = $tNGs->getRecordset("custom");
$row_rscustom = mysql_fetch_assoc($rscustom);
$totalRows_rscustom = mysql_num_rows($rscustom);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Super User Index</title>
<link href="../includes/skins/mxkollection3.css" rel="stylesheet" type="text/css" media="all" />
<script src="../includes/common/js/base.js" type="text/javascript"></script>
<script src="../includes/common/js/utility.js" type="text/javascript"></script>
<script src="../includes/skins/style.js" type="text/javascript"></script>
</head>


<style>
.logout {float: right; position: relative; top: 0px;}
table {margin: auto;}
a {color: black; text-decoration: none;}
td {border-bottom: 1px solid black; font-size: 14px; font-family: Arial, Helvetica, sans-serif;}
.table_header {background: #090; color: white; }
.header {
	font-family: Cambria, "Hoefler Text", "Liberation Serif", Times, "Times New Roman", serif;
	font-size: 18px;
	color: white;
	text-align: center;
	margin-left: 0px;
	margin-right: 0px;	
	background-color: #5D5E5D;
	font-size: 25px;
	padding: 13px;
}

.intro_text {text-align: center;}

.modal {
    display: none; 
    position: fixed; 
    z-index: 1; 
    left: 0;
    top: 0;
    width: 100%; 
    height: 100%; 
    overflow: auto; 
    background-color: rgb(0,0,0); 
    background-color: rgba(0,0,0,0.4); 
    padding-top: 60px;
	
}


button {border: 1px solid #CCC;}

.close {
    position: absolute;
    right: 120px;
    top: 90px;
    color: #000;
    font-size: 35px;
    font-weight: bold;
}

.close:hover, a:hover,
.close:focus {
    color: red;
    cursor: pointer;
}

.box {	background-color: #FFF;
	overflow: auto;
	margin-right: 100px;
	margin-left: 100px;
	margin-top: 30px;
	padding: 10px;
	border: 1px solid black;
	border-radius: 10px; }
	
	ul {
	margin-top: 0px;
	margin-right: 1px;
	margin-left: 9px;
	margin-bottom: 5px;
	padding: 0px;
	list-style: none;
	position: fixed;
	z-index: 1;
}

ul li {
	float: left;
	width: 188px;
	height: auto;
	background-color: #5D5E5D;
	line-height: 40px;
	text-align: center;
	font-size: 20px;
	font-family: Cambria, "Hoefler Text", "Liberation Serif", Times, "Times New Roman", serif;
	color: white;
}

ul li a {
	text-decoration: none;
	color: white;
	display: block;
}

ul li a:hover {
	background-color: green;
	color: white;
	
}

ul li ul li {
	display: none;
	text-align: center;
	float: none;
	width: 300px;
}

ul li:hover ul li {
	display: block;
}	
</style>



<body>
<div class="nav_bar">
<ul>
    	<li><a href="index.php">Home</a></li>
        <li><a href="../superadmin/edu.php">Education</a></li>
        <li><a href="../superadmin/env.php">Environment</a></li>
        <li><a href="../superadmin/health.php">Health</a></li>
        <li><a>Jobs</a>
        	<ul>
            	<li><a href="../superadmin/jobpost.php">Job Postings</a></li>
                <li><a href="../superadmin/jobapp.php">Job Applicants</a></li>
            </ul>
        </li>
      <li><a>Infrastructure</a>
        	<ul>
            	<li><a href="../superadmin/infradevpost.php">Development Postings</a></li>
            	<li><a href="../superadmin/infradevapp.php">Development Applicants</a></li>
            </ul>
    </li>    
              <li><?php echo $_SESSION['kt_login_user']; ?> | &#8681;
   	<ul>
            	<li>
            	  <?php
	echo $tNGs->getErrorMsg();
?>
           	    <a href="<?php echo $logoutTransaction1->getLogoutLink(); ?>">Logout</a></li>
            </ul>
        </li>    
            
  </ul>
<br />
<br />
</div>

<p class="header"><strong>MSIDS SUPER-USER PAGE</strong></p>

<p class="intro_text">Welcome to the MSIDS Super-User page. Click on the below links to get started:</p>


<table cellpadding="20" cellspacing="10">

<tr>
  <td class="table_header">Super-User</td>
  <td><a href="edu.php">Education</a></td>
  <td><a href="env.php">Environment</a></td>
  <td><a href="health.php">Health</a></td>
  <td><a onclick="document.getElementById('01').style.display='block'">Infrastructure</a></td>
  <td><a onclick="document.getElementById('02').style.display='block'">Jobs</a></td>
  <td><a href="companies.php">Companies</a></td>
  <td><a href="users.php">Users</a></td>
  <td><a href="flag_posts_list.php">Flagged Posts</a></td>	
</tr>
<tr>
  <td class="table_header">Regular User</td>
  <td><a href="edumodal.php">Education</a></td>
  <td><a href="envmodal.php">Environment</a></td>
  <td><a href="healthmodal.php">Health</a></td>
  <td><a onclick="document.getElementById('03').style.display='block'">Infrastructure</a></td>
  <td><a onclick="document.getElementById('04').style.display='block'">Jobs</a></td></tr>
	
</table>

<!-- Super User Infrastructure -->

<div class="modal" id="01" align="center">
<div class="box">
<span onclick="document.getElementById('01').style.display='none'" class="close" title="Close Modal">×</span>
<p>Please select one of the links below:</p>
<button><a href="infradevpost.php">Development Postings</a></button>
<button><a href="infradevapp.php">Development Applicants</a></button>
</div>
</div>

<!-- Super User Job -->

<div class="modal" id="02" align="center">
<div class="box">
<span onclick="document.getElementById('02').style.display='none'" class="close" title="Close Modal">×</span>
<p>Please select one of the links below:</p>
<button><a href="jobpost.php">Job Postings</a></button>
<button><a href="jobapp.php">Job Applicants</a></button>
</div>
</div>

<!-- Regular User Infrastructure -->

<div class="modal" id="03" align="center">
<div class="box">
<span onclick="document.getElementById('03').style.display='none'" class="close" title="Close Modal">×</span>
<p>Please select one of the links below:</p>
<button><a href="infradevpostmodal.php">Development Postings</a></button>
<button><a href="infradevappmodal.php">Development Applicants</a></button>
</div>
</div>

<!-- Regular User Job -->

<div class="modal" id="04" align="center">
<div class="box">
<span onclick="document.getElementById('04').style.display='none'" class="close" title="Close Modal">×</span>
<p>Please select one of the links below:</p>
<button><a href="jobpostmodal.php">Job Postings</a></button>
<button><a href="jobappmodal.php">Job Applicants</a></button>
</div>
</div>


</body>
</html>
<?php
mysql_free_result($user);
?>
