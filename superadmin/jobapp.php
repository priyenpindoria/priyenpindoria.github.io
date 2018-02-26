<?php require_once('../Connections/connMISDS.php'); ?>
<?php
// Load the common classes
require_once('../includes/common/KT_common.php');

// Load the tNG classes
require_once('../includes/tng/tNG.inc.php');

// Load the required classes
require_once('../includes/tfi/TFI.php');
require_once('../includes/tso/TSO.php');
require_once('../includes/nav/NAV.php');

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

// Filter
$tfi_listjobs_app6 = new TFI_TableFilter($conn_connMISDS, "tfi_listjobs_app6");
$tfi_listjobs_app6->addColumn("jobs_app.applicantname", "STRING_TYPE", "applicantname", "%");
$tfi_listjobs_app6->addColumn("jobs_app.jobid", "STRING_TYPE", "jobid", "%");
$tfi_listjobs_app6->addColumn("jobs_app.jobcompany", "STRING_TYPE", "jobcompany", "%");
$tfi_listjobs_app6->addColumn("jobs_app.idnumber", "NUMERIC_TYPE", "idnumber", "=");
$tfi_listjobs_app6->addColumn("jobs_app.Nationality", "STRING_TYPE", "Nationality", "%");
$tfi_listjobs_app6->addColumn("jobs_app.prevexperiences", "STRING_TYPE", "prevexperiences", "%");
$tfi_listjobs_app6->addColumn("jobs_app.dob", "DATE_TYPE", "dob", "=");
$tfi_listjobs_app6->addColumn("jobs_app.number", "STRING_TYPE", "number", "%");
$tfi_listjobs_app6->addColumn("jobs_app.email", "STRING_TYPE", "email", "%");
$tfi_listjobs_app6->Execute();

// Sorter
$tso_listjobs_app6 = new TSO_TableSorter("rsjobs_app1", "tso_listjobs_app6");
$tso_listjobs_app6->addColumn("jobs_app.applicantname");
$tso_listjobs_app6->addColumn("jobs_app.jobid");
$tso_listjobs_app6->addColumn("jobs_app.jobcompany");
$tso_listjobs_app6->addColumn("jobs_app.idnumber");
$tso_listjobs_app6->addColumn("jobs_app.Nationality");
$tso_listjobs_app6->addColumn("jobs_app.prevexperiences");
$tso_listjobs_app6->addColumn("jobs_app.dob");
$tso_listjobs_app6->addColumn("jobs_app.number");
$tso_listjobs_app6->addColumn("jobs_app.email");
$tso_listjobs_app6->setDefault("jobs_app.applicantname");
$tso_listjobs_app6->Execute();

// Navigation
$nav_listjobs_app6 = new NAV_Regular("nav_listjobs_app6", "rsjobs_app1", "../", $_SERVER['PHP_SELF'], 10);

//NeXTenesio3 Special List Recordset
$maxRows_rsjobs_app1 = $_SESSION['max_rows_nav_listjobs_app6'];
$pageNum_rsjobs_app1 = 0;
if (isset($_GET['pageNum_rsjobs_app1'])) {
  $pageNum_rsjobs_app1 = $_GET['pageNum_rsjobs_app1'];
}
$startRow_rsjobs_app1 = $pageNum_rsjobs_app1 * $maxRows_rsjobs_app1;

// Defining List Recordset variable
$NXTFilter_rsjobs_app1 = "1=1";
if (isset($_SESSION['filter_tfi_listjobs_app6'])) {
  $NXTFilter_rsjobs_app1 = $_SESSION['filter_tfi_listjobs_app6'];
}
// Defining List Recordset variable
$NXTSort_rsjobs_app1 = "jobs_app.applicantname";
if (isset($_SESSION['sorter_tso_listjobs_app6'])) {
  $NXTSort_rsjobs_app1 = $_SESSION['sorter_tso_listjobs_app6'];
}
mysql_select_db($database_connMISDS, $connMISDS);

$query_rsjobs_app1 = "SELECT jobs_app.applicantname, jobs_app.jobid, jobs_app.jobcompany, jobs_app.idnumber, jobs_app.Nationality, jobs_app.prevexperiences, jobs_app.dob, jobs_app.number, jobs_app.email, jobs_app.applicantid FROM jobs_app WHERE {$NXTFilter_rsjobs_app1} ORDER BY {$NXTSort_rsjobs_app1}";
$query_limit_rsjobs_app1 = sprintf("%s LIMIT %d, %d", $query_rsjobs_app1, $startRow_rsjobs_app1, $maxRows_rsjobs_app1);
$rsjobs_app1 = mysql_query($query_limit_rsjobs_app1, $connMISDS) or die(mysql_error());
$row_rsjobs_app1 = mysql_fetch_assoc($rsjobs_app1);

if (isset($_GET['totalRows_rsjobs_app1'])) {
  $totalRows_rsjobs_app1 = $_GET['totalRows_rsjobs_app1'];
} else {
  $all_rsjobs_app1 = mysql_query($query_rsjobs_app1);
  $totalRows_rsjobs_app1 = mysql_num_rows($all_rsjobs_app1);
}
$totalPages_rsjobs_app1 = ceil($totalRows_rsjobs_app1/$maxRows_rsjobs_app1)-1;
//End NeXTenesio3 Special List Recordset

// Make a logout transaction instance
$logoutTransaction = new tNG_logoutTransaction($conn_connMISDS);
$tNGs->addTransaction($logoutTransaction);
// Register triggers
$logoutTransaction->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "GET", "KT_logout_now");
$logoutTransaction->registerTrigger("END", "Trigger_Default_Redirect", 99, "../login.php");
// Add columns
// End of logout transaction instance

// Execute all the registered transactions
$tNGs->executeTransactions();

$nav_listjobs_app6->checkBoundries();

// Get the transaction recordset
$rscustom = $tNGs->getRecordset("custom");
$row_rscustom = mysql_fetch_assoc($rscustom);
$totalRows_rscustom = mysql_num_rows($rscustom);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Super User Job Applicants</title>
<link href="../includes/skins/mxkollection3.css" rel="stylesheet" type="text/css" media="all" />
<script src="../includes/common/js/base.js" type="text/javascript"></script>
<script src="../includes/common/js/utility.js" type="text/javascript"></script>
<script src="../includes/skins/style.js" type="text/javascript"></script>
<script src="../includes/nxt/scripts/list.js" type="text/javascript"></script>
<script src="../includes/nxt/scripts/list.js.php" type="text/javascript"></script>
<script type="text/javascript">
$NXT_LIST_SETTINGS = {
  duplicate_buttons: false,
  duplicate_navigation: false,
  row_effects: true,
  show_as_buttons: false,
  record_counter: false
}
</script>
<style type="text/css">
  /* Dynamic List row settings */
  .KT_col_applicantname {width:140px; overflow:hidden;}
  .KT_col_jobid {width:140px; overflow:hidden;}
  .KT_col_jobcompany {width:126px; overflow:hidden;}
  .KT_col_idnumber {width:126px; overflow:hidden;}
  .KT_col_Nationality {width:140px; overflow:hidden;}
  .KT_col_prevexperiences {width:140px; overflow:hidden;}
  .KT_col_dob {width:126px; overflow:hidden;}
  .KT_col_number {width:140px; overflow:hidden;}
  .KT_col_email {width:140px; overflow:hidden;}

</style>
<link rel="stylesheet" type="text/css" href="css_list_modal.css" />
</head>

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
               	<li>
               	  <?php
	echo $tNGs->getErrorMsg();
?>
           	    <a href="<?php echo $logoutTransaction->getLogoutLink(); ?>">Logout</a></li>
            
            
            
  </ul>
<br />
<br />
</div>

<p class="header">MSIDS JOB APPLICANTS - SUPER USER</p>
<div class="KT_tng" id="listjobs_app6">
  <h1> Job Applicants
    <?php
  $nav_listjobs_app6->Prepare();
  require("../includes/nav/NAV_Text_Statistics.inc.php");
?>
  </h1>
  <div class="KT_tnglist">
    <form action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>" method="post" id="form1">
      <div class="KT_options"> <a href="<?php echo $nav_listjobs_app6->getShowAllLink(); ?>"><?php echo NXT_getResource("Show"); ?>
        <?php 
  // Show IF Conditional region1
  if (@$_GET['show_all_nav_listjobs_app6'] == 1) {
?>
          <?php echo $_SESSION['default_max_rows_nav_listjobs_app6']; ?>
          <?php 
  // else Conditional region1
  } else { ?>
          <?php echo NXT_getResource("all"); ?>
          <?php } 
  // endif Conditional region1
?>
<?php echo NXT_getResource("records"); ?></a> &nbsp;
        &nbsp;
        <?php 
  // Show IF Conditional region2
  if (@$_SESSION['has_filter_tfi_listjobs_app6'] == 1) {
?>
          <a href="<?php echo $tfi_listjobs_app6->getResetFilterLink(); ?>"><?php echo NXT_getResource("Reset filter"); ?></a>
          <?php 
  // else Conditional region2
  } else { ?>
          <a href="<?php echo $tfi_listjobs_app6->getShowFilterLink(); ?>"><?php echo NXT_getResource("Show filter"); ?></a>
          <?php } 
  // endif Conditional region2
?>
      </div>
      <table cellpadding="2" cellspacing="0" class="KT_tngtable">
        <thead>
          <tr class="KT_row_order">
            <th> <input type="checkbox" name="KT_selAll" id="KT_selAll"/>
            </th>
            <th id="applicantname" class="KT_sorter KT_col_applicantname <?php echo $tso_listjobs_app6->getSortIcon('jobs_app.applicantname'); ?>"> <a href="<?php echo $tso_listjobs_app6->getSortLink('jobs_app.applicantname'); ?>">Name</a></th>
            <th id="jobid" class="KT_sorter KT_col_jobid <?php echo $tso_listjobs_app6->getSortIcon('jobs_app.jobid'); ?>"> <a href="<?php echo $tso_listjobs_app6->getSortLink('jobs_app.jobid'); ?>">Job</a></th>
            <th id="jobcompany" class="KT_sorter KT_col_jobcompany <?php echo $tso_listjobs_app6->getSortIcon('jobs_app.jobcompany'); ?>"> <a href="<?php echo $tso_listjobs_app6->getSortLink('jobs_app.jobcompany'); ?>">Company</a></th>
            <th id="idnumber" class="KT_sorter KT_col_idnumber <?php echo $tso_listjobs_app6->getSortIcon('jobs_app.idnumber'); ?>"> <a href="<?php echo $tso_listjobs_app6->getSortLink('jobs_app.idnumber'); ?>">ID Number</a></th>
            <th id="Nationality" class="KT_sorter KT_col_Nationality <?php echo $tso_listjobs_app6->getSortIcon('jobs_app.Nationality'); ?>"> <a href="<?php echo $tso_listjobs_app6->getSortLink('jobs_app.Nationality'); ?>">Nationality</a></th>
            <th id="prevexperiences" class="KT_sorter KT_col_prevexperiences <?php echo $tso_listjobs_app6->getSortIcon('jobs_app.prevexperiences'); ?>"> <a href="<?php echo $tso_listjobs_app6->getSortLink('jobs_app.prevexperiences'); ?>">Experiences</a></th>
            <th id="dob" class="KT_sorter KT_col_dob <?php echo $tso_listjobs_app6->getSortIcon('jobs_app.dob'); ?>"> <a href="<?php echo $tso_listjobs_app6->getSortLink('jobs_app.dob'); ?>">Date of Birth </a></th>
            <th id="number" class="KT_sorter KT_col_number <?php echo $tso_listjobs_app6->getSortIcon('jobs_app.number'); ?>"> <a href="<?php echo $tso_listjobs_app6->getSortLink('jobs_app.number'); ?>">Number</a></th>
            <th id="email" class="KT_sorter KT_col_email <?php echo $tso_listjobs_app6->getSortIcon('jobs_app.email'); ?>"> <a href="<?php echo $tso_listjobs_app6->getSortLink('jobs_app.email'); ?>">Email</a></th>
            <th>&nbsp;</th>
          </tr>
          <?php 
  // Show IF Conditional region3
  if (@$_SESSION['has_filter_tfi_listjobs_app6'] == 1) {
?>
            <tr class="KT_row_filter">
              <td>&nbsp;</td>
              <td><input type="text" name="tfi_listjobs_app6_applicantname" id="tfi_listjobs_app6_applicantname" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listjobs_app6_applicantname']); ?>" size="20" maxlength="100" /></td>
              <td><input type="text" name="tfi_listjobs_app6_jobid" id="tfi_listjobs_app6_jobid" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listjobs_app6_jobid']); ?>" size="20" maxlength="100" /></td>
              <td><input type="text" name="tfi_listjobs_app6_jobcompany" id="tfi_listjobs_app6_jobcompany" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listjobs_app6_jobcompany']); ?>" size="18" maxlength="100" /></td>
              <td><input type="text" name="tfi_listjobs_app6_idnumber" id="tfi_listjobs_app6_idnumber" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listjobs_app6_idnumber']); ?>" size="18" maxlength="100" /></td>
              <td><input type="text" name="tfi_listjobs_app6_Nationality" id="tfi_listjobs_app6_Nationality" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listjobs_app6_Nationality']); ?>" size="20" maxlength="50" /></td>
              <td><input type="text" name="tfi_listjobs_app6_prevexperiences" id="tfi_listjobs_app6_prevexperiences" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listjobs_app6_prevexperiences']); ?>" size="20" maxlength="100" /></td>
              <td><input type="text" name="tfi_listjobs_app6_dob" id="tfi_listjobs_app6_dob" value="<?php echo @$_SESSION['tfi_listjobs_app6_dob']; ?>" size="10" maxlength="22" /></td>
              <td><input type="text" name="tfi_listjobs_app6_number" id="tfi_listjobs_app6_number" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listjobs_app6_number']); ?>" size="20" maxlength="100" /></td>
              <td><input type="text" name="tfi_listjobs_app6_email" id="tfi_listjobs_app6_email" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listjobs_app6_email']); ?>" size="20" maxlength="100" /></td>
              <td><input type="submit" name="tfi_listjobs_app6" value="<?php echo NXT_getResource("Filter"); ?>" /></td>
            </tr>
            <?php } 
  // endif Conditional region3
?>
        </thead>
        <tbody>
          <?php if ($totalRows_rsjobs_app1 == 0) { // Show if recordset empty ?>
            <tr>
              <td colspan="11"><?php echo NXT_getResource("The table is empty or the filter you've selected is too restrictive."); ?></td>
            </tr>
            <?php } // Show if recordset empty ?>
          <?php if ($totalRows_rsjobs_app1 > 0) { // Show if recordset not empty ?>
            <?php do { ?>
              <tr class="<?php echo @$cnt1++%2==0 ? "" : "KT_even"; ?>">
                <td><input type="checkbox" name="kt_pk_jobs_app" class="id_checkbox" value="<?php echo $row_rsjobs_app1['applicantid']; ?>" />
                  <input type="hidden" name="applicantid" class="id_field" value="<?php echo $row_rsjobs_app1['applicantid']; ?>" /></td>
                <td><div class="KT_col_applicantname"><?php echo KT_FormatForList($row_rsjobs_app1['applicantname'], 20); ?></div></td>
                <td><div class="KT_col_jobid"><?php echo KT_FormatForList($row_rsjobs_app1['jobid'], 20); ?></div></td>
                <td><div class="KT_col_jobcompany"><?php echo KT_FormatForList($row_rsjobs_app1['jobcompany'], 18); ?></div></td>
                <td><div class="KT_col_idnumber"><?php echo KT_FormatForList($row_rsjobs_app1['idnumber'], 18); ?></div></td>
                <td><div class="KT_col_Nationality"><?php echo KT_FormatForList($row_rsjobs_app1['Nationality'], 20); ?></div></td>
                <td><div class="KT_col_prevexperiences"><?php echo KT_FormatForList($row_rsjobs_app1['prevexperiences'], 20); ?></div></td>
                <td><div class="KT_col_dob"><?php echo KT_formatDate($row_rsjobs_app1['dob']); ?></div></td>
                <td><div class="KT_col_number"><?php echo KT_FormatForList($row_rsjobs_app1['number'], 20); ?></div></td>
                <td><div class="KT_col_email"><?php echo KT_FormatForList($row_rsjobs_app1['email'], 20); ?></div></td>
                <td><a class="KT_edit_link" href="edit-jobapp.php?applicantid=<?php echo $row_rsjobs_app1['applicantid']; ?>&amp;KT_back=1"><?php echo NXT_getResource("edit_one"); ?></a> <a class="KT_delete_link" href="#delete"><?php echo NXT_getResource("delete_one"); ?></a></td>
              </tr>
              <?php } while ($row_rsjobs_app1 = mysql_fetch_assoc($rsjobs_app1)); ?>
            <?php } // Show if recordset not empty ?>
        </tbody>
      </table>
      <div class="KT_bottomnav">
        <div>
          <?php
            $nav_listjobs_app6->Prepare();
            require("../includes/nav/NAV_Text_Navigation.inc.php");
          ?>
        </div>
      </div>
      <div class="KT_bottombuttons">
        <div class="KT_operations"> <a class="KT_edit_op_link" href="#" onclick="nxt_list_edit_link_form(this); return false;"><?php echo NXT_getResource("edit_all"); ?></a> <a class="KT_delete_op_link" href="#" onclick="nxt_list_delete_link_form(this); return false;"><?php echo NXT_getResource("delete_all"); ?></a></div>
        <span>&nbsp;</span>
        <select name="no_new" id="no_new">
          <option value="1">1</option>
          <option value="3">3</option>
          <option value="6">6</option>
        </select>
        <a class="KT_additem_op_link" href="edit-jobapp.php?KT_back=1" onclick="return nxt_list_additem(this)"><?php echo NXT_getResource("add new"); ?></a></div>
    </form>
  </div>
  <br class="clearfixplain" />
</div>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($rsjobs_app1);
?>
