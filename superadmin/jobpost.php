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
$tfi_listjobs_post2 = new TFI_TableFilter($conn_connMISDS, "tfi_listjobs_post2");
$tfi_listjobs_post2->addColumn("jobs_post.jobname", "STRING_TYPE", "jobname", "%");
$tfi_listjobs_post2->addColumn("jobs_post.company", "STRING_TYPE", "company", "%");
$tfi_listjobs_post2->addColumn("jobs_post.number", "STRING_TYPE", "number", "%");
$tfi_listjobs_post2->addColumn("jobs_post.email", "STRING_TYPE", "email", "%");
$tfi_listjobs_post2->addColumn("jobs_post.location", "STRING_TYPE", "location", "%");
$tfi_listjobs_post2->addColumn("jobs_post.timings", "STRING_TYPE", "timings", "%");
$tfi_listjobs_post2->addColumn("jobs_post.salary", "STRING_TYPE", "salary", "%");
$tfi_listjobs_post2->addColumn("jobs_post.deadline", "DATE_TYPE", "deadline", "=");
$tfi_listjobs_post2->Execute();

// Sorter
$tso_listjobs_post2 = new TSO_TableSorter("rsjobs_post1", "tso_listjobs_post2");
$tso_listjobs_post2->addColumn("jobs_post.jobname");
$tso_listjobs_post2->addColumn("jobs_post.company");
$tso_listjobs_post2->addColumn("jobs_post.number");
$tso_listjobs_post2->addColumn("jobs_post.email");
$tso_listjobs_post2->addColumn("jobs_post.location");
$tso_listjobs_post2->addColumn("jobs_post.timings");
$tso_listjobs_post2->addColumn("jobs_post.salary");
$tso_listjobs_post2->addColumn("jobs_post.deadline");
$tso_listjobs_post2->setDefault("jobs_post.jobname");
$tso_listjobs_post2->Execute();

// Navigation
$nav_listjobs_post2 = new NAV_Regular("nav_listjobs_post2", "rsjobs_post1", "../", $_SERVER['PHP_SELF'], 10);

//NeXTenesio3 Special List Recordset
$maxRows_rsjobs_post1 = $_SESSION['max_rows_nav_listjobs_post2'];
$pageNum_rsjobs_post1 = 0;
if (isset($_GET['pageNum_rsjobs_post1'])) {
  $pageNum_rsjobs_post1 = $_GET['pageNum_rsjobs_post1'];
}
$startRow_rsjobs_post1 = $pageNum_rsjobs_post1 * $maxRows_rsjobs_post1;

// Defining List Recordset variable
$NXTFilter_rsjobs_post1 = "1=1";
if (isset($_SESSION['filter_tfi_listjobs_post2'])) {
  $NXTFilter_rsjobs_post1 = $_SESSION['filter_tfi_listjobs_post2'];
}
// Defining List Recordset variable
$NXTSort_rsjobs_post1 = "jobs_post.jobname";
if (isset($_SESSION['sorter_tso_listjobs_post2'])) {
  $NXTSort_rsjobs_post1 = $_SESSION['sorter_tso_listjobs_post2'];
}
mysql_select_db($database_connMISDS, $connMISDS);

$query_rsjobs_post1 = "SELECT jobs_post.jobname, jobs_post.company, jobs_post.number, jobs_post.email, jobs_post.location, jobs_post.timings, jobs_post.salary, jobs_post.deadline, jobs_post.jobid FROM jobs_post WHERE {$NXTFilter_rsjobs_post1} ORDER BY {$NXTSort_rsjobs_post1}";
$query_limit_rsjobs_post1 = sprintf("%s LIMIT %d, %d", $query_rsjobs_post1, $startRow_rsjobs_post1, $maxRows_rsjobs_post1);
$rsjobs_post1 = mysql_query($query_limit_rsjobs_post1, $connMISDS) or die(mysql_error());
$row_rsjobs_post1 = mysql_fetch_assoc($rsjobs_post1);

if (isset($_GET['totalRows_rsjobs_post1'])) {
  $totalRows_rsjobs_post1 = $_GET['totalRows_rsjobs_post1'];
} else {
  $all_rsjobs_post1 = mysql_query($query_rsjobs_post1);
  $totalRows_rsjobs_post1 = mysql_num_rows($all_rsjobs_post1);
}
$totalPages_rsjobs_post1 = ceil($totalRows_rsjobs_post1/$maxRows_rsjobs_post1)-1;
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

$nav_listjobs_post2->checkBoundries();

// Get the transaction recordset
$rscustom = $tNGs->getRecordset("custom");
$row_rscustom = mysql_fetch_assoc($rscustom);
$totalRows_rscustom = mysql_num_rows($rscustom);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Super User Job Postings</title>
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
  .KT_col_jobname {width:140px; overflow:hidden;}
  .KT_col_company {width:140px; overflow:hidden;}
  .KT_col_number {width:140px; overflow:hidden;}
  .KT_col_email {width:140px; overflow:hidden;}
  .KT_col_location {width:140px; overflow:hidden;}
  .KT_col_timings {width:140px; overflow:hidden;}
  .KT_col_salary {width:140px; overflow:hidden;}
  .KT_col_deadline {width:140px; overflow:hidden;}
  
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

<p class="header">MSIDS JOB POSTINGS - SUPER USER</p>
<div class="KT_tng" id="listjobs_post2">
  <h1> Job Postings
    <?php
  $nav_listjobs_post2->Prepare();
  require("../includes/nav/NAV_Text_Statistics.inc.php");
?>
  </h1>
  <div class="KT_tnglist">
    <form action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>" method="post" id="form1">
      <div class="KT_options"> <a href="<?php echo $nav_listjobs_post2->getShowAllLink(); ?>"><?php echo NXT_getResource("Show"); ?>
        <?php 
  // Show IF Conditional region1
  if (@$_GET['show_all_nav_listjobs_post2'] == 1) {
?>
          <?php echo $_SESSION['default_max_rows_nav_listjobs_post2']; ?>
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
  if (@$_SESSION['has_filter_tfi_listjobs_post2'] == 1) {
?>
          <a href="<?php echo $tfi_listjobs_post2->getResetFilterLink(); ?>"><?php echo NXT_getResource("Reset filter"); ?></a>
          <?php 
  // else Conditional region2
  } else { ?>
          <a href="<?php echo $tfi_listjobs_post2->getShowFilterLink(); ?>"><?php echo NXT_getResource("Show filter"); ?></a>
          <?php } 
  // endif Conditional region2
?>
      </div>
      <table cellpadding="2" cellspacing="0" class="KT_tngtable">
        <thead>
          <tr class="KT_row_order">
            <th> <input type="checkbox" name="KT_selAll" id="KT_selAll"/>
            </th>
            <th id="jobname" class="KT_sorter KT_col_jobname <?php echo $tso_listjobs_post2->getSortIcon('jobs_post.jobname'); ?>"> <a href="<?php echo $tso_listjobs_post2->getSortLink('jobs_post.jobname'); ?>">Name</a></th>
            <th id="company" class="KT_sorter KT_col_company <?php echo $tso_listjobs_post2->getSortIcon('jobs_post.company'); ?>"> <a href="<?php echo $tso_listjobs_post2->getSortLink('jobs_post.company'); ?>">Company</a></th>
            <th id="number" class="KT_sorter KT_col_number <?php echo $tso_listjobs_post2->getSortIcon('jobs_post.number'); ?>"> <a href="<?php echo $tso_listjobs_post2->getSortLink('jobs_post.number'); ?>">Number</a></th>
            <th id="email" class="KT_sorter KT_col_email <?php echo $tso_listjobs_post2->getSortIcon('jobs_post.email'); ?>"> <a href="<?php echo $tso_listjobs_post2->getSortLink('jobs_post.email'); ?>">Email</a></th>
            <th id="location" class="KT_sorter KT_col_location <?php echo $tso_listjobs_post2->getSortIcon('jobs_post.location'); ?>"> <a href="<?php echo $tso_listjobs_post2->getSortLink('jobs_post.location'); ?>">Location</a></th>
            <th id="timings" class="KT_sorter KT_col_timings <?php echo $tso_listjobs_post2->getSortIcon('jobs_post.timings'); ?>"> <a href="<?php echo $tso_listjobs_post2->getSortLink('jobs_post.timings'); ?>">Timings</a></th>
            <th id="salary" class="KT_sorter KT_col_salary <?php echo $tso_listjobs_post2->getSortIcon('jobs_post.salary'); ?>"> <a href="<?php echo $tso_listjobs_post2->getSortLink('jobs_post.salary'); ?>">Salary</a></th>
            <th id="deadline" class="KT_sorter KT_col_deadline <?php echo $tso_listjobs_post2->getSortIcon('jobs_post.deadline'); ?>"> <a href="<?php echo $tso_listjobs_post2->getSortLink('jobs_post.deadline'); ?>">Deadline</a></th>
            <th>&nbsp;</th>
          </tr>
          <?php 
  // Show IF Conditional region3
  if (@$_SESSION['has_filter_tfi_listjobs_post2'] == 1) {
?>
            <tr class="KT_row_filter">
              <td>&nbsp;</td>
              <td><input type="text" name="tfi_listjobs_post2_jobname" id="tfi_listjobs_post2_jobname" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listjobs_post2_jobname']); ?>" size="20" maxlength="100" /></td>
              <td><input type="text" name="tfi_listjobs_post2_company" id="tfi_listjobs_post2_company" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listjobs_post2_company']); ?>" size="20" maxlength="100" /></td>
              <td><input type="text" name="tfi_listjobs_post2_number" id="tfi_listjobs_post2_number" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listjobs_post2_number']); ?>" size="20" maxlength="100" /></td>
              <td><input type="text" name="tfi_listjobs_post2_email" id="tfi_listjobs_post2_email" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listjobs_post2_email']); ?>" size="20" maxlength="100" /></td>
              <td><input type="text" name="tfi_listjobs_post2_location" id="tfi_listjobs_post2_location" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listjobs_post2_location']); ?>" size="20" maxlength="100" /></td>
              <td><input type="text" name="tfi_listjobs_post2_timings" id="tfi_listjobs_post2_timings" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listjobs_post2_timings']); ?>" size="20" maxlength="100" /></td>
              <td><input type="text" name="tfi_listjobs_post2_salary" id="tfi_listjobs_post2_salary" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listjobs_post2_salary']); ?>" size="20" maxlength="100" /></td>
              <td><input type="text" name="tfi_listjobs_post2_deadline" id="tfi_listjobs_post2_deadline" value="<?php echo @$_SESSION['tfi_listjobs_post2_deadline']; ?>" size="10" maxlength="22" /></td>
              <td><input type="submit" name="tfi_listjobs_post2" value="<?php echo NXT_getResource("Filter"); ?>" /></td>
            </tr>
            <?php } 
  // endif Conditional region3
?>
        </thead>
        <tbody>
          <?php if ($totalRows_rsjobs_post1 == 0) { // Show if recordset empty ?>
            <tr>
              <td colspan="10"><?php echo NXT_getResource("The table is empty or the filter you've selected is too restrictive."); ?></td>
            </tr>
            <?php } // Show if recordset empty ?>
          <?php if ($totalRows_rsjobs_post1 > 0) { // Show if recordset not empty ?>
            <?php do { ?>
              <tr class="<?php echo @$cnt1++%2==0 ? "" : "KT_even"; ?>">
                <td><input type="checkbox" name="kt_pk_jobs_post" class="id_checkbox" value="<?php echo $row_rsjobs_post1['jobid']; ?>" />
                  <input type="hidden" name="jobid" class="id_field" value="<?php echo $row_rsjobs_post1['jobid']; ?>" /></td>
                <td><div class="KT_col_jobname"><?php echo KT_FormatForList($row_rsjobs_post1['jobname'], 20); ?></div></td>
                <td><div class="KT_col_company"><?php echo KT_FormatForList($row_rsjobs_post1['company'], 20); ?></div></td>
                <td><div class="KT_col_number"><?php echo KT_FormatForList($row_rsjobs_post1['number'], 20); ?></div></td>
                <td><div class="KT_col_email"><?php echo KT_FormatForList($row_rsjobs_post1['email'], 20); ?></div></td>
                <td><div class="KT_col_location"><?php echo KT_FormatForList($row_rsjobs_post1['location'], 20); ?></div></td>
                <td><div class="KT_col_timings"><?php echo KT_FormatForList($row_rsjobs_post1['timings'], 20); ?></div></td>
                <td><div class="KT_col_salary"><?php echo KT_FormatForList($row_rsjobs_post1['salary'], 20); ?></div></td>
                <td><div class="KT_col_deadline"><?php echo KT_formatDate($row_rsjobs_post1['deadline']); ?></div></td>
                <td><a class="KT_edit_link" href="edit-jobpost.php?jobid=<?php echo $row_rsjobs_post1['jobid']; ?>&amp;KT_back=1"><?php echo NXT_getResource("edit_one"); ?></a> <a class="KT_delete_link" href="#delete"><?php echo NXT_getResource("delete_one"); ?></a></td>
              </tr>
              <?php } while ($row_rsjobs_post1 = mysql_fetch_assoc($rsjobs_post1)); ?>
            <?php } // Show if recordset not empty ?>
        </tbody>
      </table>
      <div class="KT_bottomnav">
        <div>
          <?php
            $nav_listjobs_post2->Prepare();
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
        <a class="KT_additem_op_link" href="edit-jobpost.php?KT_back=1" onclick="return nxt_list_additem(this)"><?php echo NXT_getResource("add new"); ?></a></div>
    </form>
  </div>
  <br class="clearfixplain" />
</div>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($rsjobs_post1);
?>
