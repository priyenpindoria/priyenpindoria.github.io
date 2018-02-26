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
$tfi_listflag_posts1 = new TFI_TableFilter($conn_connMISDS, "tfi_listflag_posts1");
$tfi_listflag_posts1->addColumn("flag_posts.flag_category", "STRING_TYPE", "flag_category", "%");
$tfi_listflag_posts1->addColumn("flag_posts.flag_title", "STRING_TYPE", "flag_title", "%");
$tfi_listflag_posts1->addColumn("flag_posts.flag_reson", "STRING_TYPE", "flag_reson", "%");
$tfi_listflag_posts1->Execute();

// Sorter
$tso_listflag_posts1 = new TSO_TableSorter("rsflag_posts1", "tso_listflag_posts1");
$tso_listflag_posts1->addColumn("flag_posts.flag_category");
$tso_listflag_posts1->addColumn("flag_posts.flag_title");
$tso_listflag_posts1->addColumn("flag_posts.flag_reson");
$tso_listflag_posts1->setDefault("flag_posts.flag_category");
$tso_listflag_posts1->Execute();

// Navigation
$nav_listflag_posts1 = new NAV_Regular("nav_listflag_posts1", "rsflag_posts1", "../", $_SERVER['PHP_SELF'], 10);

mysql_select_db($database_connMISDS, $connMISDS);
$query_Recordset1 = "SELECT * FROM category";
$Recordset1 = mysql_query($query_Recordset1, $connMISDS) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

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

//NeXTenesio3 Special List Recordset
$maxRows_rsflag_posts1 = $_SESSION['max_rows_nav_listflag_posts1'];
$pageNum_rsflag_posts1 = 0;
if (isset($_GET['pageNum_rsflag_posts1'])) {
  $pageNum_rsflag_posts1 = $_GET['pageNum_rsflag_posts1'];
}
$startRow_rsflag_posts1 = $pageNum_rsflag_posts1 * $maxRows_rsflag_posts1;

// Defining List Recordset variable
$NXTFilter_rsflag_posts1 = "1=1";
if (isset($_SESSION['filter_tfi_listflag_posts1'])) {
  $NXTFilter_rsflag_posts1 = $_SESSION['filter_tfi_listflag_posts1'];
}
// Defining List Recordset variable
$NXTSort_rsflag_posts1 = "flag_posts.flag_category";
if (isset($_SESSION['sorter_tso_listflag_posts1'])) {
  $NXTSort_rsflag_posts1 = $_SESSION['sorter_tso_listflag_posts1'];
}
mysql_select_db($database_connMISDS, $connMISDS);

$query_rsflag_posts1 = "SELECT flag_posts.flag_category, flag_posts.flag_title, flag_posts.flag_reson, flag_posts.flag_id FROM flag_posts WHERE {$NXTFilter_rsflag_posts1} ORDER BY {$NXTSort_rsflag_posts1}";
$query_limit_rsflag_posts1 = sprintf("%s LIMIT %d, %d", $query_rsflag_posts1, $startRow_rsflag_posts1, $maxRows_rsflag_posts1);
$rsflag_posts1 = mysql_query($query_limit_rsflag_posts1, $connMISDS) or die(mysql_error());
$row_rsflag_posts1 = mysql_fetch_assoc($rsflag_posts1);

if (isset($_GET['totalRows_rsflag_posts1'])) {
  $totalRows_rsflag_posts1 = $_GET['totalRows_rsflag_posts1'];
} else {
  $all_rsflag_posts1 = mysql_query($query_rsflag_posts1);
  $totalRows_rsflag_posts1 = mysql_num_rows($all_rsflag_posts1);
}
$totalPages_rsflag_posts1 = ceil($totalRows_rsflag_posts1/$maxRows_rsflag_posts1)-1;
//End NeXTenesio3 Special List Recordset

$nav_listflag_posts1->checkBoundries();

// Get the transaction recordset
$rscustom = $tNGs->getRecordset("custom");
$row_rscustom = mysql_fetch_assoc($rscustom);
$totalRows_rscustom = mysql_num_rows($rscustom);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Flagged Posts</title>
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
  .KT_col_flag_category {width:140px; overflow:hidden;}
  .KT_col_flag_title {width:140px; overflow:hidden;}
  .KT_col_flag_reson {width:280px; overflow:hidden;}
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

<p class="header">MSIDS FLAGGED POSTS - SUPER USER</p>
<div class="KT_tng" id="listflag_posts1">
  <h1> Flagged Posts
    <?php
  $nav_listflag_posts1->Prepare();
  require("../includes/nav/NAV_Text_Statistics.inc.php");
?>
  </h1>
  <div class="KT_tnglist">
    <form action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>" method="post" id="form1">
      <div class="KT_options"> <a href="<?php echo $nav_listflag_posts1->getShowAllLink(); ?>"><?php echo NXT_getResource("Show"); ?>
        <?php 
  // Show IF Conditional region1
  if (@$_GET['show_all_nav_listflag_posts1'] == 1) {
?>
          <?php echo $_SESSION['default_max_rows_nav_listflag_posts1']; ?>
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
  if (@$_SESSION['has_filter_tfi_listflag_posts1'] == 1) {
?>
          <a href="<?php echo $tfi_listflag_posts1->getResetFilterLink(); ?>"><?php echo NXT_getResource("Reset filter"); ?></a>
          <?php 
  // else Conditional region2
  } else { ?>
          <a href="<?php echo $tfi_listflag_posts1->getShowFilterLink(); ?>"><?php echo NXT_getResource("Show filter"); ?></a>
          <?php } 
  // endif Conditional region2
?>
      </div>
      <table cellpadding="2" cellspacing="0" class="KT_tngtable">
        <thead>
          <tr class="KT_row_order">
            <th> <input type="checkbox" name="KT_selAll" id="KT_selAll"/>
            </th>
            <th id="flag_category" class="KT_sorter KT_col_flag_category <?php echo $tso_listflag_posts1->getSortIcon('flag_posts.flag_category'); ?>"> <a href="<?php echo $tso_listflag_posts1->getSortLink('flag_posts.flag_category'); ?>">Category</a></th>
            <th id="flag_title" class="KT_sorter KT_col_flag_title <?php echo $tso_listflag_posts1->getSortIcon('flag_posts.flag_title'); ?>"> <a href="<?php echo $tso_listflag_posts1->getSortLink('flag_posts.flag_title'); ?>">Title of Post</a></th>
            <th id="flag_reson" class="KT_sorter KT_col_flag_reson <?php echo $tso_listflag_posts1->getSortIcon('flag_posts.flag_reson'); ?>"> <a href="<?php echo $tso_listflag_posts1->getSortLink('flag_posts.flag_reson'); ?>">Reason</a></th>
            <th>&nbsp;</th>
          </tr>
          <?php 
  // Show IF Conditional region3
  if (@$_SESSION['has_filter_tfi_listflag_posts1'] == 1) {
?>
            <tr class="KT_row_filter">
              <td>&nbsp;</td>
              <td><select name="tfi_listflag_posts1_flag_category" id="tfi_listflag_posts1_flag_category">
                <option value="" <?php if (!(strcmp("", @$_SESSION['tfi_listflag_posts1_flag_category']))) {echo "SELECTED";} ?>><?php echo NXT_getResource("None"); ?></option>
                <?php
do {  
?>
                <option value="<?php echo $row_Recordset1['cat_nm']?>"<?php if (!(strcmp($row_Recordset1['cat_nm'], @$_SESSION['tfi_listflag_posts1_flag_category']))) {echo "SELECTED";} ?>><?php echo $row_Recordset1['cat_nm']?></option>
                <?php
} while ($row_Recordset1 = mysql_fetch_assoc($Recordset1));
  $rows = mysql_num_rows($Recordset1);
  if($rows > 0) {
      mysql_data_seek($Recordset1, 0);
	  $row_Recordset1 = mysql_fetch_assoc($Recordset1);
  }
?>
              </select></td>
              <td><input type="text" name="tfi_listflag_posts1_flag_title" id="tfi_listflag_posts1_flag_title" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listflag_posts1_flag_title']); ?>" size="20" maxlength="100" /></td>
              <td><input type="text" name="tfi_listflag_posts1_flag_reson" id="tfi_listflag_posts1_flag_reson" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listflag_posts1_flag_reson']); ?>" size="40" maxlength="100" /></td>
              <td><input type="submit" name="tfi_listflag_posts1" value="<?php echo NXT_getResource("Filter"); ?>" /></td>
            </tr>
            <?php } 
  // endif Conditional region3
?>
        </thead>
        <tbody>
          <?php if ($totalRows_rsflag_posts1 == 0) { // Show if recordset empty ?>
            <tr>
              <td colspan="5"><?php echo NXT_getResource("The table is empty or the filter you've selected is too restrictive."); ?></td>
            </tr>
            <?php } // Show if recordset empty ?>
          <?php if ($totalRows_rsflag_posts1 > 0) { // Show if recordset not empty ?>
            <?php do { ?>
              <tr class="<?php echo @$cnt1++%2==0 ? "" : "KT_even"; ?>">
                <td><input type="checkbox" name="kt_pk_flag_posts" class="id_checkbox" value="<?php echo $row_rsflag_posts1['flag_id']; ?>" />
                  <input type="hidden" name="flag_id" class="id_field" value="<?php echo $row_rsflag_posts1['flag_id']; ?>" /></td>
                <td><div class="KT_col_flag_category"><?php echo KT_FormatForList($row_rsflag_posts1['flag_category'], 20); ?></div></td>
                <td><div class="KT_col_flag_title"><?php echo KT_FormatForList($row_rsflag_posts1['flag_title'], 20); ?></div></td>
                <td><div class="KT_col_flag_reson"><?php echo KT_FormatForList($row_rsflag_posts1['flag_reson'], 40); ?></div></td>
                <td><a class="KT_edit_link" href="flag_posts.php?flag_id=<?php echo $row_rsflag_posts1['flag_id']; ?>&amp;KT_back=1"><?php echo NXT_getResource("edit_one"); ?></a> <a class="KT_delete_link" href="#delete"><?php echo NXT_getResource("delete_one"); ?></a></td>
              </tr>
              <?php } while ($row_rsflag_posts1 = mysql_fetch_assoc($rsflag_posts1)); ?>
            <?php } // Show if recordset not empty ?>
        </tbody>
      </table>
      <div class="KT_bottomnav">
        <div>
          <?php
            $nav_listflag_posts1->Prepare();
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
        <a class="KT_additem_op_link" href="flag_posts.php?KT_back=1" onclick="return nxt_list_additem(this)"><?php echo NXT_getResource("add new"); ?></a></div>
    </form>
  </div>
  <br class="clearfixplain" />
</div>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($Recordset1);

mysql_free_result($rsflag_posts1);
?>
