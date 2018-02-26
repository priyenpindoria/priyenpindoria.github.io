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
$tfi_listhealth12 = new TFI_TableFilter($conn_connMISDS, "tfi_listhealth12");
$tfi_listhealth12->addColumn("health.subsector", "STRING_TYPE", "subsector", "%");
$tfi_listhealth12->addColumn("health.title", "STRING_TYPE", "title", "%");
$tfi_listhealth12->addColumn("health.description", "STRING_TYPE", "description", "%");
$tfi_listhealth12->addColumn("health.date", "DATE_TYPE", "date", "=");
$tfi_listhealth12->Execute();

// Sorter
$tso_listhealth12 = new TSO_TableSorter("rshealth1", "tso_listhealth12");
$tso_listhealth12->addColumn("health.subsector");
$tso_listhealth12->addColumn("health.title");
$tso_listhealth12->addColumn("health.description");
$tso_listhealth12->addColumn("health.date");
$tso_listhealth12->setDefault("health.subsector");
$tso_listhealth12->Execute();

// Navigation
$nav_listhealth12 = new NAV_Regular("nav_listhealth12", "rshealth1", "../", $_SERVER['PHP_SELF'], 10);

//NeXTenesio3 Special List Recordset
$maxRows_rshealth1 = $_SESSION['max_rows_nav_listhealth12'];
$pageNum_rshealth1 = 0;
if (isset($_GET['pageNum_rshealth1'])) {
  $pageNum_rshealth1 = $_GET['pageNum_rshealth1'];
}
$startRow_rshealth1 = $pageNum_rshealth1 * $maxRows_rshealth1;

// Defining List Recordset variable
$NXTFilter_rshealth1 = "1=1";
if (isset($_SESSION['filter_tfi_listhealth12'])) {
  $NXTFilter_rshealth1 = $_SESSION['filter_tfi_listhealth12'];
}
// Defining List Recordset variable
$NXTSort_rshealth1 = "health.subsector";
if (isset($_SESSION['sorter_tso_listhealth12'])) {
  $NXTSort_rshealth1 = $_SESSION['sorter_tso_listhealth12'];
}
mysql_select_db($database_connMISDS, $connMISDS);

$query_rshealth1 = "SELECT health.subsector, health.title, health.description, health.date, health.newsid FROM health WHERE {$NXTFilter_rshealth1} ORDER BY {$NXTSort_rshealth1}";
$query_limit_rshealth1 = sprintf("%s LIMIT %d, %d", $query_rshealth1, $startRow_rshealth1, $maxRows_rshealth1);
$rshealth1 = mysql_query($query_limit_rshealth1, $connMISDS) or die(mysql_error());
$row_rshealth1 = mysql_fetch_assoc($rshealth1);

if (isset($_GET['totalRows_rshealth1'])) {
  $totalRows_rshealth1 = $_GET['totalRows_rshealth1'];
} else {
  $all_rshealth1 = mysql_query($query_rshealth1);
  $totalRows_rshealth1 = mysql_num_rows($all_rshealth1);
}
$totalPages_rshealth1 = ceil($totalRows_rshealth1/$maxRows_rshealth1)-1;
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

$nav_listhealth12->checkBoundries();

// Get the transaction recordset
$rscustom = $tNGs->getRecordset("custom");
$row_rscustom = mysql_fetch_assoc($rscustom);
$totalRows_rscustom = mysql_num_rows($rscustom);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Super User Health</title>
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
  .KT_col_subsector {width:140px; overflow:hidden;}
  .KT_col_title {width:140px; overflow:hidden;}
  .KT_col_description {width:770px; overflow:hidden;}
  .KT_col_date {width:140px; overflow:hidden;}
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

<p class="header">MSIDS HEALTH - SUPER USER</p>

<div class="KT_tng" id="listhealth12">
  <h1> Health
    <?php
  $nav_listhealth12->Prepare();
  require("../includes/nav/NAV_Text_Statistics.inc.php");
?>
  </h1>
  <div class="KT_tnglist">
    <form action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>" method="post" id="form1">
      <div class="KT_options"> <a href="<?php echo $nav_listhealth12->getShowAllLink(); ?>"><?php echo NXT_getResource("Show"); ?>
        <?php 
  // Show IF Conditional region1
  if (@$_GET['show_all_nav_listhealth12'] == 1) {
?>
          <?php echo $_SESSION['default_max_rows_nav_listhealth12']; ?>
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
  if (@$_SESSION['has_filter_tfi_listhealth12'] == 1) {
?>
          <a href="<?php echo $tfi_listhealth12->getResetFilterLink(); ?>"><?php echo NXT_getResource("Reset filter"); ?></a>
          <?php 
  // else Conditional region2
  } else { ?>
          <a href="<?php echo $tfi_listhealth12->getShowFilterLink(); ?>"><?php echo NXT_getResource("Show filter"); ?></a>
          <?php } 
  // endif Conditional region2
?>
      </div>
      <table cellpadding="2" cellspacing="0" class="KT_tngtable">
        <thead>
          <tr class="KT_row_order">
            <th> <input type="checkbox" name="KT_selAll" id="KT_selAll"/>
            </th>
            <th id="subsector" class="KT_sorter KT_col_subsector <?php echo $tso_listhealth12->getSortIcon('health.subsector'); ?>"> <a href="<?php echo $tso_listhealth12->getSortLink('health.subsector'); ?>">Sub Sector</a></th>
            <th id="title" class="KT_sorter KT_col_title <?php echo $tso_listhealth12->getSortIcon('health.title'); ?>"> <a href="<?php echo $tso_listhealth12->getSortLink('health.title'); ?>">Title</a></th>
            <th id="description" class="KT_sorter KT_col_description <?php echo $tso_listhealth12->getSortIcon('health.description'); ?>"> <a href="<?php echo $tso_listhealth12->getSortLink('health.description'); ?>">Description</a></th>
            <th id="date" class="KT_sorter KT_col_date <?php echo $tso_listhealth12->getSortIcon('health.date'); ?>"> <a href="<?php echo $tso_listhealth12->getSortLink('health.date'); ?>">Date</a></th>
            <th>&nbsp;</th>
          </tr>
          <?php 
  // Show IF Conditional region3
  if (@$_SESSION['has_filter_tfi_listhealth12'] == 1) {
?>
            <tr class="KT_row_filter">
              <td>&nbsp;</td>
              <td><input type="text" name="tfi_listhealth12_subsector" id="tfi_listhealth12_subsector" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listhealth12_subsector']); ?>" size="20" maxlength="100" /></td>
              <td><input type="text" name="tfi_listhealth12_title" id="tfi_listhealth12_title" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listhealth12_title']); ?>" size="20" maxlength="100" /></td>
              <td><input type="text" name="tfi_listhealth12_description" id="tfi_listhealth12_description" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listhealth12_description']); ?>" size="110" maxlength="100" /></td>
              <td><input type="text" name="tfi_listhealth12_date" id="tfi_listhealth12_date" value="<?php echo @$_SESSION['tfi_listhealth12_date']; ?>" size="10" maxlength="22" /></td>
              <td><input type="submit" name="tfi_listhealth12" value="<?php echo NXT_getResource("Filter"); ?>" /></td>
            </tr>
            <?php } 
  // endif Conditional region3
?>
        </thead>
        <tbody>
          <?php if ($totalRows_rshealth1 == 0) { // Show if recordset empty ?>
            <tr>
              <td colspan="6"><?php echo NXT_getResource("The table is empty or the filter you've selected is too restrictive."); ?></td>
            </tr>
            <?php } // Show if recordset empty ?>
          <?php if ($totalRows_rshealth1 > 0) { // Show if recordset not empty ?>
            <?php do { ?>
              <tr class="<?php echo @$cnt1++%2==0 ? "" : "KT_even"; ?>">
                <td><input type="checkbox" name="kt_pk_health" class="id_checkbox" value="<?php echo $row_rshealth1['newsid']; ?>" />
                  <input type="hidden" name="newsid" class="id_field" value="<?php echo $row_rshealth1['newsid']; ?>" /></td>
                <td><div class="KT_col_subsector"><?php echo KT_FormatForList($row_rshealth1['subsector'], 20); ?></div></td>
                <td><div class="KT_col_title"><?php echo KT_FormatForList($row_rshealth1['title'], 20); ?></div></td>
                <td><div class="KT_col_description"><?php echo KT_FormatForList($row_rshealth1['description'], 110); ?></div></td>
                <td><div class="KT_col_date"><?php echo KT_formatDate($row_rshealth1['date']); ?></div></td>
                <td><a class="KT_edit_link" href="edit-health.php?newsid=<?php echo $row_rshealth1['newsid']; ?>&amp;KT_back=1"><?php echo NXT_getResource("edit_one"); ?></a> <a class="KT_delete_link" href="#delete"><?php echo NXT_getResource("delete_one"); ?></a></td>
              </tr>
              <?php } while ($row_rshealth1 = mysql_fetch_assoc($rshealth1)); ?>
            <?php } // Show if recordset not empty ?>
        </tbody>
      </table>
      <div class="KT_bottomnav">
        <div>
          <?php
            $nav_listhealth12->Prepare();
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
        <a class="KT_additem_op_link" href="edit-health.php?KT_back=1" onclick="return nxt_list_additem(this)"><?php echo NXT_getResource("add new"); ?></a></div>
    </form>
  </div>
  <br class="clearfixplain" />
</div>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($rshealth1);
?>
