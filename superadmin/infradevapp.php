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
$tfi_listinfradev_app2 = new TFI_TableFilter($conn_connMISDS, "tfi_listinfradev_app2");
$tfi_listinfradev_app2->addColumn("infradev_app.project", "STRING_TYPE", "project", "%");
$tfi_listinfradev_app2->addColumn("infradev_app.developer_company", "STRING_TYPE", "developer_company", "%");
$tfi_listinfradev_app2->addColumn("infradev_app.company", "STRING_TYPE", "company", "%");
$tfi_listinfradev_app2->addColumn("infradev_app.cost", "STRING_TYPE", "cost", "%");
$tfi_listinfradev_app2->addColumn("infradev_app.startdate", "DATE_TYPE", "startdate", "=");
$tfi_listinfradev_app2->addColumn("infradev_app.enddate", "DATE_TYPE", "enddate", "=");
$tfi_listinfradev_app2->Execute();

// Sorter
$tso_listinfradev_app2 = new TSO_TableSorter("rsinfradev_app1", "tso_listinfradev_app2");
$tso_listinfradev_app2->addColumn("infradev_app.project");
$tso_listinfradev_app2->addColumn("infradev_app.developer_company");
$tso_listinfradev_app2->addColumn("infradev_app.company");
$tso_listinfradev_app2->addColumn("infradev_app.cost");
$tso_listinfradev_app2->addColumn("infradev_app.startdate");
$tso_listinfradev_app2->addColumn("infradev_app.enddate");
$tso_listinfradev_app2->setDefault("infradev_app.project");
$tso_listinfradev_app2->Execute();

// Navigation
$nav_listinfradev_app2 = new NAV_Regular("nav_listinfradev_app2", "rsinfradev_app1", "../", $_SERVER['PHP_SELF'], 10);

//NeXTenesio3 Special List Recordset
$maxRows_rsinfradev_app1 = $_SESSION['max_rows_nav_listinfradev_app2'];
$pageNum_rsinfradev_app1 = 0;
if (isset($_GET['pageNum_rsinfradev_app1'])) {
  $pageNum_rsinfradev_app1 = $_GET['pageNum_rsinfradev_app1'];
}
$startRow_rsinfradev_app1 = $pageNum_rsinfradev_app1 * $maxRows_rsinfradev_app1;

// Defining List Recordset variable
$NXTFilter_rsinfradev_app1 = "1=1";
if (isset($_SESSION['filter_tfi_listinfradev_app2'])) {
  $NXTFilter_rsinfradev_app1 = $_SESSION['filter_tfi_listinfradev_app2'];
}
// Defining List Recordset variable
$NXTSort_rsinfradev_app1 = "infradev_app.project";
if (isset($_SESSION['sorter_tso_listinfradev_app2'])) {
  $NXTSort_rsinfradev_app1 = $_SESSION['sorter_tso_listinfradev_app2'];
}
mysql_select_db($database_connMISDS, $connMISDS);

$query_rsinfradev_app1 = "SELECT infradev_app.project, infradev_app.developer_company, infradev_app.company, infradev_app.cost, infradev_app.startdate, infradev_app.enddate, infradev_app.post_id FROM infradev_app WHERE {$NXTFilter_rsinfradev_app1} ORDER BY {$NXTSort_rsinfradev_app1}";
$query_limit_rsinfradev_app1 = sprintf("%s LIMIT %d, %d", $query_rsinfradev_app1, $startRow_rsinfradev_app1, $maxRows_rsinfradev_app1);
$rsinfradev_app1 = mysql_query($query_limit_rsinfradev_app1, $connMISDS) or die(mysql_error());
$row_rsinfradev_app1 = mysql_fetch_assoc($rsinfradev_app1);

if (isset($_GET['totalRows_rsinfradev_app1'])) {
  $totalRows_rsinfradev_app1 = $_GET['totalRows_rsinfradev_app1'];
} else {
  $all_rsinfradev_app1 = mysql_query($query_rsinfradev_app1);
  $totalRows_rsinfradev_app1 = mysql_num_rows($all_rsinfradev_app1);
}
$totalPages_rsinfradev_app1 = ceil($totalRows_rsinfradev_app1/$maxRows_rsinfradev_app1)-1;
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

$nav_listinfradev_app2->checkBoundries();

// Get the transaction recordset
$rscustom = $tNGs->getRecordset("custom");
$row_rscustom = mysql_fetch_assoc($rscustom);
$totalRows_rscustom = mysql_num_rows($rscustom);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Super User Development Applicant</title>
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
  .KT_col_project {width:140px; overflow:hidden;}
  .KT_col_developer_company {width:140px; overflow:hidden;}
  .KT_col_company {width:140px; overflow:hidden;}
  .KT_col_cost {width:140px; overflow:hidden;}
  .KT_col_startdate {width:140px; overflow:hidden;}
  .KT_col_enddate {width:140px; overflow:hidden;}

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

<p class="header">MSIDS DEVELOPMENT APPLICANTS - SUPER USER</p>
<div class="KT_tng" id="listinfradev_app2">
  <h1> Development Applicants
    <?php
  $nav_listinfradev_app2->Prepare();
  require("../includes/nav/NAV_Text_Statistics.inc.php");
?>
  </h1>
  <div class="KT_tnglist">
    <form action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>" method="post" id="form1">
      <div class="KT_options"> <a href="<?php echo $nav_listinfradev_app2->getShowAllLink(); ?>"><?php echo NXT_getResource("Show"); ?>
        <?php 
  // Show IF Conditional region1
  if (@$_GET['show_all_nav_listinfradev_app2'] == 1) {
?>
          <?php echo $_SESSION['default_max_rows_nav_listinfradev_app2']; ?>
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
  if (@$_SESSION['has_filter_tfi_listinfradev_app2'] == 1) {
?>
          <a href="<?php echo $tfi_listinfradev_app2->getResetFilterLink(); ?>"><?php echo NXT_getResource("Reset filter"); ?></a>
          <?php 
  // else Conditional region2
  } else { ?>
          <a href="<?php echo $tfi_listinfradev_app2->getShowFilterLink(); ?>"><?php echo NXT_getResource("Show filter"); ?></a>
          <?php } 
  // endif Conditional region2
?>
      </div>
      <table cellpadding="2" cellspacing="0" class="KT_tngtable">
        <thead>
          <tr class="KT_row_order">
            <th> <input type="checkbox" name="KT_selAll" id="KT_selAll"/>
            </th>
            <th id="project" class="KT_sorter KT_col_project <?php echo $tso_listinfradev_app2->getSortIcon('infradev_app.project'); ?>"> <a href="<?php echo $tso_listinfradev_app2->getSortLink('infradev_app.project'); ?>">Project</a></th>
            <th id="developer_company" class="KT_sorter KT_col_developer_company <?php echo $tso_listinfradev_app2->getSortIcon('infradev_app.developer_company'); ?>"> <a href="<?php echo $tso_listinfradev_app2->getSortLink('infradev_app.developer_company'); ?>">Developer Company</a></th>
            <th id="company" class="KT_sorter KT_col_company <?php echo $tso_listinfradev_app2->getSortIcon('infradev_app.company'); ?>"> <a href="<?php echo $tso_listinfradev_app2->getSortLink('infradev_app.company'); ?>">Company</a></th>
            <th id="cost" class="KT_sorter KT_col_cost <?php echo $tso_listinfradev_app2->getSortIcon('infradev_app.cost'); ?>"> <a href="<?php echo $tso_listinfradev_app2->getSortLink('infradev_app.cost'); ?>">Cost</a></th>
            <th id="startdate" class="KT_sorter KT_col_startdate <?php echo $tso_listinfradev_app2->getSortIcon('infradev_app.startdate'); ?>"> <a href="<?php echo $tso_listinfradev_app2->getSortLink('infradev_app.startdate'); ?>">Start Date</a></th>
            <th id="enddate" class="KT_sorter KT_col_enddate <?php echo $tso_listinfradev_app2->getSortIcon('infradev_app.enddate'); ?>"> <a href="<?php echo $tso_listinfradev_app2->getSortLink('infradev_app.enddate'); ?>">End Date</a></th>
            <th>&nbsp;</th>
          </tr>
          <?php 
  // Show IF Conditional region3
  if (@$_SESSION['has_filter_tfi_listinfradev_app2'] == 1) {
?>
            <tr class="KT_row_filter">
              <td>&nbsp;</td>
              <td><input type="text" name="tfi_listinfradev_app2_project" id="tfi_listinfradev_app2_project" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listinfradev_app2_project']); ?>" size="20" maxlength="100" /></td>
              <td><input type="text" name="tfi_listinfradev_app2_developer_company" id="tfi_listinfradev_app2_developer_company" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listinfradev_app2_developer_company']); ?>" size="20" maxlength="100" /></td>
              <td><input type="text" name="tfi_listinfradev_app2_company" id="tfi_listinfradev_app2_company" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listinfradev_app2_company']); ?>" size="20" maxlength="100" /></td>
              <td><input type="text" name="tfi_listinfradev_app2_cost" id="tfi_listinfradev_app2_cost" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listinfradev_app2_cost']); ?>" size="20" maxlength="100" /></td>
              <td><input type="text" name="tfi_listinfradev_app2_startdate" id="tfi_listinfradev_app2_startdate" value="<?php echo @$_SESSION['tfi_listinfradev_app2_startdate']; ?>" size="10" maxlength="22" /></td>
              <td><input type="text" name="tfi_listinfradev_app2_enddate" id="tfi_listinfradev_app2_enddate" value="<?php echo @$_SESSION['tfi_listinfradev_app2_enddate']; ?>" size="10" maxlength="22" /></td>
              <td><input type="submit" name="tfi_listinfradev_app2" value="<?php echo NXT_getResource("Filter"); ?>" /></td>
            </tr>
            <?php } 
  // endif Conditional region3
?>
        </thead>
        <tbody>
          <?php if ($totalRows_rsinfradev_app1 == 0) { // Show if recordset empty ?>
            <tr>
              <td colspan="8"><?php echo NXT_getResource("The table is empty or the filter you've selected is too restrictive."); ?></td>
            </tr>
            <?php } // Show if recordset empty ?>
          <?php if ($totalRows_rsinfradev_app1 > 0) { // Show if recordset not empty ?>
            <?php do { ?>
              <tr class="<?php echo @$cnt1++%2==0 ? "" : "KT_even"; ?>">
                <td><input type="checkbox" name="kt_pk_infradev_app" class="id_checkbox" value="<?php echo $row_rsinfradev_app1['post_id']; ?>" />
                  <input type="hidden" name="post_id" class="id_field" value="<?php echo $row_rsinfradev_app1['post_id']; ?>" /></td>
                <td><div class="KT_col_project"><?php echo KT_FormatForList($row_rsinfradev_app1['project'], 20); ?></div></td>
                <td><div class="KT_col_developer_company"><?php echo KT_FormatForList($row_rsinfradev_app1['developer_company'], 20); ?></div></td>
                <td><div class="KT_col_company"><?php echo KT_FormatForList($row_rsinfradev_app1['company'], 20); ?></div></td>
                <td><div class="KT_col_cost"><?php echo KT_FormatForList($row_rsinfradev_app1['cost'], 20); ?></div></td>
                <td><div class="KT_col_startdate"><?php echo KT_formatDate($row_rsinfradev_app1['startdate']); ?></div></td>
                <td><div class="KT_col_enddate"><?php echo KT_formatDate($row_rsinfradev_app1['enddate']); ?></div></td>
                <td><a class="KT_edit_link" href="edit-infradevapp.php?post_id=<?php echo $row_rsinfradev_app1['post_id']; ?>&amp;KT_back=1"><?php echo NXT_getResource("edit_one"); ?></a> <a class="KT_delete_link" href="#delete"><?php echo NXT_getResource("delete_one"); ?></a></td>
              </tr>
              <?php } while ($row_rsinfradev_app1 = mysql_fetch_assoc($rsinfradev_app1)); ?>
            <?php } // Show if recordset not empty ?>
        </tbody>
      </table>
      <div class="KT_bottomnav">
        <div>
          <?php
            $nav_listinfradev_app2->Prepare();
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
        <a class="KT_additem_op_link" href="edit-infradevapp.php?KT_back=1" onclick="return nxt_list_additem(this)"><?php echo NXT_getResource("add new"); ?></a></div>
    </form>
  </div>
  <br class="clearfixplain" />
</div>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($rsinfradev_app1);
?>
