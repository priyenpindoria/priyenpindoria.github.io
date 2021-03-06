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
$restrict->addLevel("2");
$restrict->addLevel("3");
$restrict->Execute();
//End Restrict Access To Page

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
<title>MSIDS Homepage</title>
<link rel="stylesheet" type="text/css" href="index.css" />
<link href="../includes/skins/mxkollection3.css" rel="stylesheet" type="text/css" media="all" />
<script src="../includes/common/js/base.js" type="text/javascript"></script>
<script src="../includes/common/js/utility.js" type="text/javascript"></script>
<script src="../includes/skins/style.js" type="text/javascript"></script>
<link rel="shortcut icon" type="image/png" href="../images/logo.png">
</head>
<style>
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
        <li><a href="../superadmin/edumodal.php">Education</a></li>
        <li><a href="../superadmin/envmodal.php">Environment</a></li>
        <li><a href="../superadmin/healthmodal.php">Health</a></li>
        <li><a>Jobs</a>
        	<ul>
            	<li><a href="../superadmin/jobpostmodal.php">Job Postings</a></li>
                <li><a href="../superadmin/jobappmodal.php">Job Applicants</a></li>
            </ul>
        </li>
      <li><a>Infrastructure</a>
        	<ul>
            	<li><a href="../superadmin/infradevpostmodal.php">Development Postings</a></li>
            	<li><a href="../superadmin/infradevappmodal.php">Development Applicants</a></li>
            </ul>
    </li>    
              <li><?php echo $_SESSION['kt_login_user']; ?> | &#8681;
   	<ul>
            	<li><a href="<?php echo $logoutTransaction1->getLogoutLink(); ?>">Logout</a></li>
            </ul>
        </li>    
            
    </ul>
<br />
<br />
</div>

<!-- End of CSS -->

<body>
<!-- Main Container -->
<div class="container"> 
  <!-- Header -->
  <header class="header">
    <h4 class="logo">MULTI-SECTOR INFORMATION DISTRIBUTION SYSTEM    
  </header>
  <!-- Hero Section -->
  <section class="intro">
    <div class="column"><img src="../images/logo.png" alt="" class="profile"> </div>
    <div class="column">
      <p>The MSIDS system is a web-based system that allows all users to freely and easily access information on various sectors of the county. These sectors include education, environment, infrastructure, jobs and health. The system is a free-to-all meaning that anyone can freely and easily post any small piece of information and therefore even the smallest piece of informtion if transfered over to the public allowing for a better and more efficient system at relaying of information that other commercial sites. </p>
      
    </div>
	  </section>
	  
	<div><p class="text_column">Welcome to MSIDS, <br/>
	 Click on any of the sectors below to view their respective information:
		 </p></div>
	  
  <!-- Stats Gallery Section -->
  <div class="gallery">
    <div class="thumbnail"> <a href="../superadmin/healthmodal.php"><img src="../images/health.jpg" alt="" width="2000" class="cards"/></a>
      <h4><a style="color: #52BAD5; text-decoration: none;" href="../superadmin/healthmodal.php">HEALTH</a></h4>
     
      <p class="text_column" style="padding-bottom: 25px;">Includes information such as medical schemes, disease outbraeks and medical news in the county.</p>
    </div>
    <div class="thumbnail"> <a href="../superadmin/infradevpostmodal.php"><img src="../images/infrastructure.jpg" alt="" width="2000" class="cards"/></a>
      <h4><a style="color: #52BAD5; text-decoration: none;" href="../superadmin/infradevpostmodal.php">INFRASTRUCTURE DEVELOPMENT</a></h4>
     
      <p class="text_column" style="padding-bottom: 6px;">Contains information for current construction projects available in the county.</p>
    </div>
    <div class="thumbnail"> <a href="../superadmin/edumodal.php"><img src="../images/education.jpg" alt="" width="2000" class="cards"/></a>
      <h4><a style="color: #52BAD5; text-decoration: none;" href="../superadmin/edumodal.php">EDUCATION</a></h4>
      
      <p class="text_column">Obtain information on newly established educational facilities and various educational news in the county.</p>
    </div>
    <div class="thumbnail"> <a href="../superadmin/envmodal.php"><img src="../images/env.jpg" alt="" width="2000" class="cards"/></a>
      <h4><a style="color: #52BAD5; text-decoration: none;" href="../superadmin/envmodal.php">ENVIRONMENT</a></h4>
      
      <p class="text_column" style="padding-bottom: 40px;">Join in the latest enviromental care schemes and get information on pollution news and environmental facilities in the neighbourhood.</p>
    </div>
  </div>		
  <div class="gallery">
    <div class="thumbnail"> <a href="../superadmin/jobpostmodal.php"><img src="../images/job.png" alt="" width="2000" class="cards"/></a>
      <h4><a style="color: #52BAD5; text-decoration: none;" href="../superadmin/jobpostmodal.php">JOBS</h4>
     
      <p class="text_column">Get information on vacant jobs that are currently available around the county.</p>
		
		
		
		
    </div>
    

</body>
</html>