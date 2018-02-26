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
$tfi_listeducation2 = new TFI_TableFilter($conn_connMISDS, "tfi_listeducation2");
$tfi_listeducation2->addColumn("education.educationid", "STRING_TYPE", "educationid", "%");
$tfi_listeducation2->addColumn("education.educname", "STRING_TYPE", "educname", "%");
$tfi_listeducation2->addColumn("education.educdesc", "STRING_TYPE", "educdesc", "%");
$tfi_listeducation2->addColumn("education.educloca", "STRING_TYPE", "educloca", "%");
$tfi_listeducation2->addColumn("education.educdate", "DATE_TYPE", "educdate", "=");
$tfi_listeducation2->Execute();

// Sorter
$tso_listeducation2 = new TSO_TableSorter("rseducation1", "tso_listeducation2");
$tso_listeducation2->addColumn("education.educationid");
$tso_listeducation2->addColumn("education.educname");
$tso_listeducation2->addColumn("education.educdesc");
$tso_listeducation2->addColumn("education.educloca");
$tso_listeducation2->addColumn("education.educdate");
$tso_listeducation2->setDefault("education.educationid");
$tso_listeducation2->Execute();

// Navigation
$nav_listeducation2 = new NAV_Regular("nav_listeducation2", "rseducation1", "../", $_SERVER['PHP_SELF'], 10);

//NeXTenesio3 Special List Recordset
$maxRows_rseducation1 = $_SESSION['max_rows_nav_listeducation2'];
$pageNum_rseducation1 = 0;
if (isset($_GET['pageNum_rseducation1'])) {
  $pageNum_rseducation1 = $_GET['pageNum_rseducation1'];
}
$startRow_rseducation1 = $pageNum_rseducation1 * $maxRows_rseducation1;

// Defining List Recordset variable
$NXTFilter_rseducation1 = "1=1";
if (isset($_SESSION['filter_tfi_listeducation2'])) {
  $NXTFilter_rseducation1 = $_SESSION['filter_tfi_listeducation2'];
}
// Defining List Recordset variable
$NXTSort_rseducation1 = "education.educationid";
if (isset($_SESSION['sorter_tso_listeducation2'])) {
  $NXTSort_rseducation1 = $_SESSION['sorter_tso_listeducation2'];
}
mysql_select_db($database_connMISDS, $connMISDS);

$query_rseducation1 = "SELECT education.educationid, education.educname, education.educdesc, education.educloca, education.educdate, education.educid FROM education WHERE {$NXTFilter_rseducation1} ORDER BY {$NXTSort_rseducation1}";
$query_limit_rseducation1 = sprintf("%s LIMIT %d, %d", $query_rseducation1, $startRow_rseducation1, $maxRows_rseducation1);
$rseducation1 = mysql_query($query_limit_rseducation1, $connMISDS) or die(mysql_error());
$row_rseducation1 = mysql_fetch_assoc($rseducation1);

if (isset($_GET['totalRows_rseducation1'])) {
  $totalRows_rseducation1 = $_GET['totalRows_rseducation1'];
} else {
  $all_rseducation1 = mysql_query($query_rseducation1);
  $totalRows_rseducation1 = mysql_num_rows($all_rseducation1);
}
$totalPages_rseducation1 = ceil($totalRows_rseducation1/$maxRows_rseducation1)-1;
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

$nav_listeducation2->checkBoundries();

// Get the transaction recordset
$rscustom = $tNGs->getRecordset("custom");
$row_rscustom = mysql_fetch_assoc($rscustom);
$totalRows_rscustom = mysql_num_rows($rscustom);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Super User Education</title>
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
  .KT_col_educationid {width:140px; overflow:hidden;}
  .KT_col_educname {width:140px; overflow:hidden;}
  .KT_col_educdesc {width:630px; overflow:hidden;}
  .KT_col_educloca {width:140px; overflow:hidden;}
  .KT_col_educdate {width:140px; overflow:hidden;}
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
<p class="header">MSIDS EDUCATION - SUPER USER</p>

<div class="KT_tng" id="listeducation2">
  <h1> Education
    <?php
  $nav_listeducation2->Prepare();
  require("../includes/nav/NAV_Text_Statistics.inc.php");
?>
  </h1>
  <div class="KT_tnglist">
    <form action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>" method="post" id="form1">
      <div class="KT_options"> <a href="<?php echo $nav_listeducation2->getShowAllLink(); ?>"><?php echo NXT_getResource("Show"); ?>
        <?php 
  // Show IF Conditional region1
  if (@$_GET['show_all_nav_listeducation2'] == 1) {
?>
          <?php echo $_SESSION['default_max_rows_nav_listeducation2']; ?>
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
  if (@$_SESSION['has_filter_tfi_listeducation2'] == 1) {
?>
          <a href="<?php echo $tfi_listeducation2->getResetFilterLink(); ?>"><?php echo NXT_getResource("Reset filter"); ?></a>
          <?php 
  // else Conditional region2
  } else { ?>
          <a href="<?php echo $tfi_listeducation2->getShowFilterLink(); ?>"><?php echo NXT_getResource("Show filter"); ?></a>
          <?php } 
  // endif Conditional region2
?>
      </div>
      <table cellpadding="2" cellspacing="0" class="KT_tngtable">
        <thead>
          <tr class="KT_row_order">
            <th> <input type="checkbox" name="KT_selAll" id="KT_selAll"/>
            </th>
            <th id="educationid" class="KT_sorter KT_col_educationid <?php echo $tso_listeducation2->getSortIcon('education.educationid'); ?>"> <a href="<?php echo $tso_listeducation2->getSortLink('education.educationid'); ?>">Sub Sector</a></th>
            <th id="educname" class="KT_sorter KT_col_educname <?php echo $tso_listeducation2->getSortIcon('education.educname'); ?>"> <a href="<?php echo $tso_listeducation2->getSortLink('education.educname'); ?>">Title</a></th>
            <th id="educdesc" class="KT_sorter KT_col_educdesc <?php echo $tso_listeducation2->getSortIcon('education.educdesc'); ?>"> <a href="<?php echo $tso_listeducation2->getSortLink('education.educdesc'); ?>">Description</a></th>
            <th id="educloca" class="KT_sorter KT_col_educloca <?php echo $tso_listeducation2->getSortIcon('education.educloca'); ?>"> <a href="<?php echo $tso_listeducation2->getSortLink('education.educloca'); ?>">Location</a></th>
            <th id="educdate" class="KT_sorter KT_col_educdate <?php echo $tso_listeducation2->getSortIcon('education.educdate'); ?>"> <a href="<?php echo $tso_listeducation2->getSortLink('education.educdate'); ?>">Date</a></th>
            <th>&nbsp;</th>
          </tr>
          <?php 
  // Show IF Conditional region3
  if (@$_SESSION['has_filter_tfi_listeducation2'] == 1) {
?>
            <tr class="KT_row_filter">
              <td>&nbsp;</td>
              <td><input type="text" name="tfi_listeducation2_educationid" id="tfi_listeducation2_educationid" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listeducation2_educationid']); ?>" size="20" maxlength="100" /></td>
              <td><input type="text" name="tfi_listeducation2_educname" id="tfi_listeducation2_educname" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listeducation2_educname']); ?>" size="20" maxlength="100" /></td>
              <td><input type="text" name="tfi_listeducation2_educdesc" id="tfi_listeducation2_educdesc" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listeducation2_educdesc']); ?>" size="90" maxlength="100" /></td>
              <td><input type="text" name="tfi_listeducation2_educloca" id="tfi_listeducation2_educloca" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listeducation2_educloca']); ?>" size="20" maxlength="100" /></td>
              <td><input type="text" name="tfi_listeducation2_educdate" id="tfi_listeducation2_educdate" value="<?php echo @$_SESSION['tfi_listeducation2_educdate']; ?>" size="10" maxlength="22" /></td>
              <td><input type="submit" name="tfi_listeducation2" value="<?php echo NXT_getResource("Filter"); ?>" /></td>
            </tr>
            <?php } 
  // endif Conditional region3
?>
        </thead>
        <tbody>
          <?php if ($totalRows_rseducation1 == 0) { // Show if recordset empty ?>
            <tr>
              <td colspan="7"><?php echo NXT_getResource("The table is empty or the filter you've selected is too restrictive."); ?></td>
            </tr>
            <?php } // Show if recordset empty ?>
          <?php if ($totalRows_rseducation1 > 0) { // Show if recordset not empty ?>
            <?php do { ?>
              <tr class="<?php echo @$cnt1++%2==0 ? "" : "KT_even"; ?>">
                <td><input type="checkbox" name="kt_pk_education" class="id_checkbox" value="<?php echo $row_rseducation1['educid']; ?>" />
                  <input type="hidden" name="educid" class="id_field" value="<?php echo $row_rseducation1['educid']; ?>" /></td>
                <td><div class="KT_col_educationid"><?php echo KT_FormatForList($row_rseducation1['educationid'], 20); ?></div></td>
                <td><div class="KT_col_educname"><?php echo KT_FormatForList($row_rseducation1['educname'], 20); ?></div></td>
                <td><div class="KT_col_educdesc"><?php echo KT_FormatForList($row_rseducation1['educdesc'], 90); ?></div></td>
                <td><div class="KT_col_educloca"><?php echo KT_FormatForList($row_rseducation1['educloca'], 20); ?></div></td>
                <td><div class="KT_col_educdate"><?php echo KT_formatDate($row_rseducation1['educdate']); ?></div></td>
                <td><a class="KT_edit_link" href="edit-edu.php?educid=<?php echo $row_rseducation1['educid']; ?>&amp;KT_back=1"><?php echo NXT_getResource("edit_one"); ?></a> <a class="KT_delete_link" href="#delete"><?php echo NXT_getResource("delete_one"); ?></a></td>
              </tr>
              <?php } while ($row_rseducation1 = mysql_fetch_assoc($rseducation1)); ?>
            <?php } // Show if recordset not empty ?>
        </tbody>
      </table>
      <div class="KT_bottomnav">
        <div>
          <?php
            $nav_listeducation2->Prepare();
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
        <a class="KT_additem_op_link" href="edit-edu.php?KT_back=1" onclick="return nxt_list_additem(this)"><?php echo NXT_getResource("add new"); ?></a></div>
    </form>
  </div>
  <br class="clearfixplain" />
</div>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($rseducation1);
?>
