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
$tfi_listinfradev_post8 = new TFI_TableFilter($conn_connMISDS, "tfi_listinfradev_post8");
$tfi_listinfradev_post8->addColumn("infradev_post.devname", "STRING_TYPE", "devname", "%");
$tfi_listinfradev_post8->addColumn("infradev_post.devdesc", "STRING_TYPE", "devdesc", "%");
$tfi_listinfradev_post8->addColumn("infradev_post.devcompany", "STRING_TYPE", "devcompany", "%");
$tfi_listinfradev_post8->addColumn("infradev_post.devnumber", "NUMERIC_TYPE", "devnumber", "=");
$tfi_listinfradev_post8->addColumn("infradev_post.devemail", "STRING_TYPE", "devemail", "%");
$tfi_listinfradev_post8->addColumn("infradev_post.devlocation", "STRING_TYPE", "devlocation", "%");
$tfi_listinfradev_post8->addColumn("infradev_post.devstdate", "DATE_TYPE", "devstdate", "=");
$tfi_listinfradev_post8->addColumn("infradev_post.devduedate", "DATE_TYPE", "devduedate", "=");
$tfi_listinfradev_post8->Execute();

// Sorter
$tso_listinfradev_post8 = new TSO_TableSorter("rsinfradev_post1", "tso_listinfradev_post8");
$tso_listinfradev_post8->addColumn("infradev_post.devname");
$tso_listinfradev_post8->addColumn("infradev_post.devdesc");
$tso_listinfradev_post8->addColumn("infradev_post.devcompany");
$tso_listinfradev_post8->addColumn("infradev_post.devnumber");
$tso_listinfradev_post8->addColumn("infradev_post.devemail");
$tso_listinfradev_post8->addColumn("infradev_post.devlocation");
$tso_listinfradev_post8->addColumn("infradev_post.devstdate");
$tso_listinfradev_post8->addColumn("infradev_post.devduedate");
$tso_listinfradev_post8->setDefault("infradev_post.devname");
$tso_listinfradev_post8->Execute();

// Navigation
$nav_listinfradev_post8 = new NAV_Regular("nav_listinfradev_post8", "rsinfradev_post1", "../", $_SERVER['PHP_SELF'], 10);

//NeXTenesio3 Special List Recordset
$maxRows_rsinfradev_post1 = $_SESSION['max_rows_nav_listinfradev_post8'];
$pageNum_rsinfradev_post1 = 0;
if (isset($_GET['pageNum_rsinfradev_post1'])) {
  $pageNum_rsinfradev_post1 = $_GET['pageNum_rsinfradev_post1'];
}
$startRow_rsinfradev_post1 = $pageNum_rsinfradev_post1 * $maxRows_rsinfradev_post1;

// Defining List Recordset variable
$NXTFilter_rsinfradev_post1 = "1=1";
if (isset($_SESSION['filter_tfi_listinfradev_post8'])) {
  $NXTFilter_rsinfradev_post1 = $_SESSION['filter_tfi_listinfradev_post8'];
}
// Defining List Recordset variable
$NXTSort_rsinfradev_post1 = "infradev_post.devname";
if (isset($_SESSION['sorter_tso_listinfradev_post8'])) {
  $NXTSort_rsinfradev_post1 = $_SESSION['sorter_tso_listinfradev_post8'];
}
mysql_select_db($database_connMISDS, $connMISDS);

$query_rsinfradev_post1 = "SELECT infradev_post.devname, infradev_post.devdesc, infradev_post.devcompany, infradev_post.devnumber, infradev_post.devemail, infradev_post.devlocation, infradev_post.devstdate, infradev_post.devduedate, infradev_post.devid FROM infradev_post WHERE {$NXTFilter_rsinfradev_post1} ORDER BY {$NXTSort_rsinfradev_post1}";
$query_limit_rsinfradev_post1 = sprintf("%s LIMIT %d, %d", $query_rsinfradev_post1, $startRow_rsinfradev_post1, $maxRows_rsinfradev_post1);
$rsinfradev_post1 = mysql_query($query_limit_rsinfradev_post1, $connMISDS) or die(mysql_error());
$row_rsinfradev_post1 = mysql_fetch_assoc($rsinfradev_post1);

if (isset($_GET['totalRows_rsinfradev_post1'])) {
  $totalRows_rsinfradev_post1 = $_GET['totalRows_rsinfradev_post1'];
} else {
  $all_rsinfradev_post1 = mysql_query($query_rsinfradev_post1);
  $totalRows_rsinfradev_post1 = mysql_num_rows($all_rsinfradev_post1);
}
$totalPages_rsinfradev_post1 = ceil($totalRows_rsinfradev_post1/$maxRows_rsinfradev_post1)-1;
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

$nav_listinfradev_post8->checkBoundries();

// Get the transaction recordset
$rscustom = $tNGs->getRecordset("custom");
$row_rscustom = mysql_fetch_assoc($rscustom);
$totalRows_rscustom = mysql_num_rows($rscustom);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Super User Development Posting</title>
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
  .KT_col_devname {width:140px; overflow:hidden;}
  .KT_col_devdesc {width:140px; overflow:hidden;}
  .KT_col_devcompany {width:140px; overflow:hidden;}
  .KT_col_devnumber {width:140px; overflow:hidden;}
  .KT_col_devemail {width:140px; overflow:hidden;}
  .KT_col_devlocation {width:140px; overflow:hidden;}
  .KT_col_devstdate {width:140px; overflow:hidden;}
  .KT_col_devduedate {width:140px; overflow:hidden;}
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

<p class="header">MSIDS DEVELOPMENT POSTINGS - SUPER USER</p>
<div class="KT_tng" id="listinfradev_post8">
  <h1> Development Postings
    <?php
  $nav_listinfradev_post8->Prepare();
  require("../includes/nav/NAV_Text_Statistics.inc.php");
?>
  </h1>
  <div class="KT_tnglist">
    <form action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>" method="post" id="form1">
      <div class="KT_options"> <a href="<?php echo $nav_listinfradev_post8->getShowAllLink(); ?>"><?php echo NXT_getResource("Show"); ?>
        <?php 
  // Show IF Conditional region1
  if (@$_GET['show_all_nav_listinfradev_post8'] == 1) {
?>
          <?php echo $_SESSION['default_max_rows_nav_listinfradev_post8']; ?>
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
  if (@$_SESSION['has_filter_tfi_listinfradev_post8'] == 1) {
?>
          <a href="<?php echo $tfi_listinfradev_post8->getResetFilterLink(); ?>"><?php echo NXT_getResource("Reset filter"); ?></a>
          <?php 
  // else Conditional region2
  } else { ?>
          <a href="<?php echo $tfi_listinfradev_post8->getShowFilterLink(); ?>"><?php echo NXT_getResource("Show filter"); ?></a>
          <?php } 
  // endif Conditional region2
?>
      </div>
      <table cellpadding="2" cellspacing="0" class="KT_tngtable">
        <thead>
          <tr class="KT_row_order">
            <th> <input type="checkbox" name="KT_selAll" id="KT_selAll"/>
            </th>
            <th id="devname" class="KT_sorter KT_col_devname <?php echo $tso_listinfradev_post8->getSortIcon('infradev_post.devname'); ?>"> <a href="<?php echo $tso_listinfradev_post8->getSortLink('infradev_post.devname'); ?>">Title</a></th>
            <th id="devdesc" class="KT_sorter KT_col_devdesc <?php echo $tso_listinfradev_post8->getSortIcon('infradev_post.devdesc'); ?>"> <a href="<?php echo $tso_listinfradev_post8->getSortLink('infradev_post.devdesc'); ?>">Description</a></th>
            <th id="devcompany" class="KT_sorter KT_col_devcompany <?php echo $tso_listinfradev_post8->getSortIcon('infradev_post.devcompany'); ?>"> <a href="<?php echo $tso_listinfradev_post8->getSortLink('infradev_post.devcompany'); ?>">Company</a></th>
            <th id="devnumber" class="KT_sorter KT_col_devnumber <?php echo $tso_listinfradev_post8->getSortIcon('infradev_post.devnumber'); ?>"> <a href="<?php echo $tso_listinfradev_post8->getSortLink('infradev_post.devnumber'); ?>">Number</a></th>
            <th id="devemail" class="KT_sorter KT_col_devemail <?php echo $tso_listinfradev_post8->getSortIcon('infradev_post.devemail'); ?>"> <a href="<?php echo $tso_listinfradev_post8->getSortLink('infradev_post.devemail'); ?>">Email</a></th>
            <th id="devlocation" class="KT_sorter KT_col_devlocation <?php echo $tso_listinfradev_post8->getSortIcon('infradev_post.devlocation'); ?>"> <a href="<?php echo $tso_listinfradev_post8->getSortLink('infradev_post.devlocation'); ?>">Location</a></th>
            <th id="devstdate" class="KT_sorter KT_col_devstdate <?php echo $tso_listinfradev_post8->getSortIcon('infradev_post.devstdate'); ?>"> <a href="<?php echo $tso_listinfradev_post8->getSortLink('infradev_post.devstdate'); ?>">Start Date</a></th>
            <th id="devduedate" class="KT_sorter KT_col_devduedate <?php echo $tso_listinfradev_post8->getSortIcon('infradev_post.devduedate'); ?>"> <a href="<?php echo $tso_listinfradev_post8->getSortLink('infradev_post.devduedate'); ?>">Completion Date</a></th>
            <th>&nbsp;</th>
          </tr>
          <?php 
  // Show IF Conditional region3
  if (@$_SESSION['has_filter_tfi_listinfradev_post8'] == 1) {
?>
            <tr class="KT_row_filter">
              <td>&nbsp;</td>
              <td><input type="text" name="tfi_listinfradev_post8_devname" id="tfi_listinfradev_post8_devname" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listinfradev_post8_devname']); ?>" size="20" maxlength="100" /></td>
              <td><input type="text" name="tfi_listinfradev_post8_devdesc" id="tfi_listinfradev_post8_devdesc" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listinfradev_post8_devdesc']); ?>" size="20" maxlength="100" /></td>
              <td><input type="text" name="tfi_listinfradev_post8_devcompany" id="tfi_listinfradev_post8_devcompany" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listinfradev_post8_devcompany']); ?>" size="20" maxlength="100" /></td>
              <td><input type="text" name="tfi_listinfradev_post8_devnumber" id="tfi_listinfradev_post8_devnumber" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listinfradev_post8_devnumber']); ?>" size="20" maxlength="100" /></td>
              <td><input type="text" name="tfi_listinfradev_post8_devemail" id="tfi_listinfradev_post8_devemail" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listinfradev_post8_devemail']); ?>" size="20" maxlength="100" /></td>
              <td><input type="text" name="tfi_listinfradev_post8_devlocation" id="tfi_listinfradev_post8_devlocation" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listinfradev_post8_devlocation']); ?>" size="20" maxlength="100" /></td>
              <td><input type="text" name="tfi_listinfradev_post8_devstdate" id="tfi_listinfradev_post8_devstdate" value="<?php echo @$_SESSION['tfi_listinfradev_post8_devstdate']; ?>" size="10" maxlength="22" /></td>
              <td><input type="text" name="tfi_listinfradev_post8_devduedate" id="tfi_listinfradev_post8_devduedate" value="<?php echo @$_SESSION['tfi_listinfradev_post8_devduedate']; ?>" size="10" maxlength="22" /></td>
              <td><input type="submit" name="tfi_listinfradev_post8" value="<?php echo NXT_getResource("Filter"); ?>" /></td>
            </tr>
            <?php } 
  // endif Conditional region3
?>
        </thead>
        <tbody>
          <?php if ($totalRows_rsinfradev_post1 == 0) { // Show if recordset empty ?>
            <tr>
              <td colspan="10"><?php echo NXT_getResource("The table is empty or the filter you've selected is too restrictive."); ?></td>
            </tr>
            <?php } // Show if recordset empty ?>
          <?php if ($totalRows_rsinfradev_post1 > 0) { // Show if recordset not empty ?>
            <?php do { ?>
              <tr class="<?php echo @$cnt1++%2==0 ? "" : "KT_even"; ?>">
                <td><input type="checkbox" name="kt_pk_infradev_post" class="id_checkbox" value="<?php echo $row_rsinfradev_post1['devid']; ?>" />
                  <input type="hidden" name="devid" class="id_field" value="<?php echo $row_rsinfradev_post1['devid']; ?>" /></td>
                <td><div class="KT_col_devname"><?php echo KT_FormatForList($row_rsinfradev_post1['devname'], 20); ?></div></td>
                <td><div class="KT_col_devdesc"><?php echo KT_FormatForList($row_rsinfradev_post1['devdesc'], 20); ?></div></td>
                <td><div class="KT_col_devcompany"><?php echo KT_FormatForList($row_rsinfradev_post1['devcompany'], 20); ?></div></td>
                <td><div class="KT_col_devnumber"><?php echo KT_FormatForList($row_rsinfradev_post1['devnumber'], 20); ?></div></td>
                <td><div class="KT_col_devemail"><?php echo KT_FormatForList($row_rsinfradev_post1['devemail'], 20); ?></div></td>
                <td><div class="KT_col_devlocation"><?php echo KT_FormatForList($row_rsinfradev_post1['devlocation'], 20); ?></div></td>
                <td><div class="KT_col_devstdate"><?php echo KT_formatDate($row_rsinfradev_post1['devstdate']); ?></div></td>
                <td><div class="KT_col_devduedate"><?php echo KT_formatDate($row_rsinfradev_post1['devduedate']); ?></div></td>
                <td><a class="KT_edit_link" href="edit-infradevpost.php?devid=<?php echo $row_rsinfradev_post1['devid']; ?>&amp;KT_back=1"><?php echo NXT_getResource("edit_one"); ?></a> <a class="KT_delete_link" href="#delete"><?php echo NXT_getResource("delete_one"); ?></a></td>
              </tr>
              <?php } while ($row_rsinfradev_post1 = mysql_fetch_assoc($rsinfradev_post1)); ?>
            <?php } // Show if recordset not empty ?>
        </tbody>
      </table>
      <div class="KT_bottomnav">
        <div>
          <?php
            $nav_listinfradev_post8->Prepare();
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
        <a class="KT_additem_op_link" href="edit-infradevpost.php?KT_back=1" onclick="return nxt_list_additem(this)"><?php echo NXT_getResource("add new"); ?></a></div>
    </form>
  </div>
  <br class="clearfixplain" />
</div>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($rsinfradev_post1);
?>
