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
$tfi_listenvironment6 = new TFI_TableFilter($conn_connMISDS, "tfi_listenvironment6");
$tfi_listenvironment6->addColumn("environment.envsubsec", "STRING_TYPE", "envsubsec", "%");
$tfi_listenvironment6->addColumn("environment.environname", "STRING_TYPE", "environname", "%");
$tfi_listenvironment6->addColumn("environment.environdesc", "STRING_TYPE", "environdesc", "%");
$tfi_listenvironment6->addColumn("environment.environloca", "STRING_TYPE", "environloca", "%");
$tfi_listenvironment6->addColumn("environment.environdate", "DATE_TYPE", "environdate", "=");
$tfi_listenvironment6->Execute();

// Sorter
$tso_listenvironment6 = new TSO_TableSorter("rsenvironment1", "tso_listenvironment6");
$tso_listenvironment6->addColumn("environment.envsubsec");
$tso_listenvironment6->addColumn("environment.environname");
$tso_listenvironment6->addColumn("environment.environdesc");
$tso_listenvironment6->addColumn("environment.environloca");
$tso_listenvironment6->addColumn("environment.environdate");
$tso_listenvironment6->setDefault("environment.envsubsec");
$tso_listenvironment6->Execute();

// Navigation
$nav_listenvironment6 = new NAV_Regular("nav_listenvironment6", "rsenvironment1", "../", $_SERVER['PHP_SELF'], 10);

//NeXTenesio3 Special List Recordset
$maxRows_rsenvironment1 = $_SESSION['max_rows_nav_listenvironment6'];
$pageNum_rsenvironment1 = 0;
if (isset($_GET['pageNum_rsenvironment1'])) {
  $pageNum_rsenvironment1 = $_GET['pageNum_rsenvironment1'];
}
$startRow_rsenvironment1 = $pageNum_rsenvironment1 * $maxRows_rsenvironment1;

// Defining List Recordset variable
$NXTFilter_rsenvironment1 = "1=1";
if (isset($_SESSION['filter_tfi_listenvironment6'])) {
  $NXTFilter_rsenvironment1 = $_SESSION['filter_tfi_listenvironment6'];
}
// Defining List Recordset variable
$NXTSort_rsenvironment1 = "environment.envsubsec";
if (isset($_SESSION['sorter_tso_listenvironment6'])) {
  $NXTSort_rsenvironment1 = $_SESSION['sorter_tso_listenvironment6'];
}
mysql_select_db($database_connMISDS, $connMISDS);

$query_rsenvironment1 = "SELECT environment.envsubsec, environment.environname, environment.environdesc, environment.environloca, environment.environdate, environment.environid FROM environment WHERE {$NXTFilter_rsenvironment1} ORDER BY {$NXTSort_rsenvironment1}";
$query_limit_rsenvironment1 = sprintf("%s LIMIT %d, %d", $query_rsenvironment1, $startRow_rsenvironment1, $maxRows_rsenvironment1);
$rsenvironment1 = mysql_query($query_limit_rsenvironment1, $connMISDS) or die(mysql_error());
$row_rsenvironment1 = mysql_fetch_assoc($rsenvironment1);

if (isset($_GET['totalRows_rsenvironment1'])) {
  $totalRows_rsenvironment1 = $_GET['totalRows_rsenvironment1'];
} else {
  $all_rsenvironment1 = mysql_query($query_rsenvironment1);
  $totalRows_rsenvironment1 = mysql_num_rows($all_rsenvironment1);
}
$totalPages_rsenvironment1 = ceil($totalRows_rsenvironment1/$maxRows_rsenvironment1)-1;
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

$nav_listenvironment6->checkBoundries();

// Get the transaction recordset
$rscustom = $tNGs->getRecordset("custom");
$row_rscustom = mysql_fetch_assoc($rscustom);
$totalRows_rscustom = mysql_num_rows($rscustom);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Super User Environment</title>
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
  .KT_col_envsubsec {width:140px; overflow:hidden;}
  .KT_col_environname {width:140px; overflow:hidden;}
  .KT_col_environdesc {width:595px; overflow:hidden;}
  .KT_col_environloca {width:140px; overflow:hidden;}
  .KT_col_environdate {width:140px; overflow:hidden;}
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
<p class="header">MSIDS ENVIRONMENT - SUPER USER</p>

<div class="KT_tng" id="listenvironment6">
  <h1> Environment
    <?php
  $nav_listenvironment6->Prepare();
  require("../includes/nav/NAV_Text_Statistics.inc.php");
?>
  </h1>
  <div class="KT_tnglist">
    <form action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>" method="post" id="form1">
      <div class="KT_options"> <a href="<?php echo $nav_listenvironment6->getShowAllLink(); ?>"><?php echo NXT_getResource("Show"); ?>
        <?php 
  // Show IF Conditional region1
  if (@$_GET['show_all_nav_listenvironment6'] == 1) {
?>
          <?php echo $_SESSION['default_max_rows_nav_listenvironment6']; ?>
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
  if (@$_SESSION['has_filter_tfi_listenvironment6'] == 1) {
?>
          <a href="<?php echo $tfi_listenvironment6->getResetFilterLink(); ?>"><?php echo NXT_getResource("Reset filter"); ?></a>
          <?php 
  // else Conditional region2
  } else { ?>
          <a href="<?php echo $tfi_listenvironment6->getShowFilterLink(); ?>"><?php echo NXT_getResource("Show filter"); ?></a>
          <?php } 
  // endif Conditional region2
?>
      </div>
      <table cellpadding="2" cellspacing="0" class="KT_tngtable">
        <thead>
          <tr class="KT_row_order">
            <th> <input type="checkbox" name="KT_selAll" id="KT_selAll"/>
            </th>
            <th id="envsubsec" class="KT_sorter KT_col_envsubsec <?php echo $tso_listenvironment6->getSortIcon('environment.envsubsec'); ?>"> <a href="<?php echo $tso_listenvironment6->getSortLink('environment.envsubsec'); ?>">Sub Sector</a></th>
            <th id="environname" class="KT_sorter KT_col_environname <?php echo $tso_listenvironment6->getSortIcon('environment.environname'); ?>"> <a href="<?php echo $tso_listenvironment6->getSortLink('environment.environname'); ?>">Title</a></th>
            <th id="environdesc" class="KT_sorter KT_col_environdesc <?php echo $tso_listenvironment6->getSortIcon('environment.environdesc'); ?>"> <a href="<?php echo $tso_listenvironment6->getSortLink('environment.environdesc'); ?>">Description</a></th>
            <th id="environloca" class="KT_sorter KT_col_environloca <?php echo $tso_listenvironment6->getSortIcon('environment.environloca'); ?>"> <a href="<?php echo $tso_listenvironment6->getSortLink('environment.environloca'); ?>">Location</a></th>
            <th id="environdate" class="KT_sorter KT_col_environdate <?php echo $tso_listenvironment6->getSortIcon('environment.environdate'); ?>"> <a href="<?php echo $tso_listenvironment6->getSortLink('environment.environdate'); ?>">Date</a></th>
            <th>&nbsp;</th>
          </tr>
          <?php 
  // Show IF Conditional region3
  if (@$_SESSION['has_filter_tfi_listenvironment6'] == 1) {
?>
            <tr class="KT_row_filter">
              <td>&nbsp;</td>
              <td><input type="text" name="tfi_listenvironment6_envsubsec" id="tfi_listenvironment6_envsubsec" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listenvironment6_envsubsec']); ?>" size="20" maxlength="100" /></td>
              <td><input type="text" name="tfi_listenvironment6_environname" id="tfi_listenvironment6_environname" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listenvironment6_environname']); ?>" size="20" maxlength="100" /></td>
              <td><input type="text" name="tfi_listenvironment6_environdesc" id="tfi_listenvironment6_environdesc" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listenvironment6_environdesc']); ?>" size="85" maxlength="100" /></td>
              <td><input type="text" name="tfi_listenvironment6_environloca" id="tfi_listenvironment6_environloca" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listenvironment6_environloca']); ?>" size="20" maxlength="100" /></td>
              <td><input type="text" name="tfi_listenvironment6_environdate" id="tfi_listenvironment6_environdate" value="<?php echo @$_SESSION['tfi_listenvironment6_environdate']; ?>" size="10" maxlength="22" /></td>
              <td><input type="submit" name="tfi_listenvironment6" value="<?php echo NXT_getResource("Filter"); ?>" /></td>
            </tr>
            <?php } 
  // endif Conditional region3
?>
        </thead>
        <tbody>
          <?php if ($totalRows_rsenvironment1 == 0) { // Show if recordset empty ?>
            <tr>
              <td colspan="7"><?php echo NXT_getResource("The table is empty or the filter you've selected is too restrictive."); ?></td>
            </tr>
            <?php } // Show if recordset empty ?>
          <?php if ($totalRows_rsenvironment1 > 0) { // Show if recordset not empty ?>
            <?php do { ?>
              <tr class="<?php echo @$cnt1++%2==0 ? "" : "KT_even"; ?>">
                <td><input type="checkbox" name="kt_pk_environment" class="id_checkbox" value="<?php echo $row_rsenvironment1['environid']; ?>" />
                  <input type="hidden" name="environid" class="id_field" value="<?php echo $row_rsenvironment1['environid']; ?>" /></td>
                <td><div class="KT_col_envsubsec"><?php echo KT_FormatForList($row_rsenvironment1['envsubsec'], 20); ?></div></td>
                <td><div class="KT_col_environname"><?php echo KT_FormatForList($row_rsenvironment1['environname'], 20); ?></div></td>
                <td><div class="KT_col_environdesc"><?php echo KT_FormatForList($row_rsenvironment1['environdesc'], 85); ?></div></td>
                <td><div class="KT_col_environloca"><?php echo KT_FormatForList($row_rsenvironment1['environloca'], 20); ?></div></td>
                <td><div class="KT_col_environdate"><?php echo KT_formatDate($row_rsenvironment1['environdate']); ?></div></td>
                <td><a class="KT_edit_link" href="edit-env.php?environid=<?php echo $row_rsenvironment1['environid']; ?>&amp;KT_back=1"><?php echo NXT_getResource("edit_one"); ?></a> <a class="KT_delete_link" href="#delete"><?php echo NXT_getResource("delete_one"); ?></a></td>
              </tr>
              <?php } while ($row_rsenvironment1 = mysql_fetch_assoc($rsenvironment1)); ?>
            <?php } // Show if recordset not empty ?>
        </tbody>
      </table>
      <div class="KT_bottomnav">
        <div>
          <?php
            $nav_listenvironment6->Prepare();
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
        <a class="KT_additem_op_link" href="edit-env.php?KT_back=1" onclick="return nxt_list_additem(this)"><?php echo NXT_getResource("add new"); ?></a></div>
    </form>
  </div>
  <br class="clearfixplain" />
</div>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($rsenvironment1);
?>
