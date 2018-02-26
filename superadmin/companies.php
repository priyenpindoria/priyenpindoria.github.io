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
$tfi_listcompanies2 = new TFI_TableFilter($conn_connMISDS, "tfi_listcompanies2");
$tfi_listcompanies2->addColumn("companies.companyname", "STRING_TYPE", "companyname", "%");
$tfi_listcompanies2->addColumn("companies.companynumber", "NUMERIC_TYPE", "companynumber", "=");
$tfi_listcompanies2->addColumn("companies.companyemail", "STRING_TYPE", "companyemail", "%");
$tfi_listcompanies2->Execute();

// Sorter
$tso_listcompanies2 = new TSO_TableSorter("rscompanies1", "tso_listcompanies2");
$tso_listcompanies2->addColumn("companies.companyname");
$tso_listcompanies2->addColumn("companies.companynumber");
$tso_listcompanies2->addColumn("companies.companyemail");
$tso_listcompanies2->setDefault("companies.companyname");
$tso_listcompanies2->Execute();

// Navigation
$nav_listcompanies2 = new NAV_Regular("nav_listcompanies2", "rscompanies1", "../", $_SERVER['PHP_SELF'], 10);

//NeXTenesio3 Special List Recordset
$maxRows_rscompanies1 = $_SESSION['max_rows_nav_listcompanies2'];
$pageNum_rscompanies1 = 0;
if (isset($_GET['pageNum_rscompanies1'])) {
  $pageNum_rscompanies1 = $_GET['pageNum_rscompanies1'];
}
$startRow_rscompanies1 = $pageNum_rscompanies1 * $maxRows_rscompanies1;

// Defining List Recordset variable
$NXTFilter_rscompanies1 = "1=1";
if (isset($_SESSION['filter_tfi_listcompanies2'])) {
  $NXTFilter_rscompanies1 = $_SESSION['filter_tfi_listcompanies2'];
}
// Defining List Recordset variable
$NXTSort_rscompanies1 = "companies.companyname";
if (isset($_SESSION['sorter_tso_listcompanies2'])) {
  $NXTSort_rscompanies1 = $_SESSION['sorter_tso_listcompanies2'];
}
mysql_select_db($database_connMISDS, $connMISDS);

$query_rscompanies1 = "SELECT companies.companyname, companies.companynumber, companies.companyemail FROM companies WHERE {$NXTFilter_rscompanies1} ORDER BY {$NXTSort_rscompanies1}";
$query_limit_rscompanies1 = sprintf("%s LIMIT %d, %d", $query_rscompanies1, $startRow_rscompanies1, $maxRows_rscompanies1);
$rscompanies1 = mysql_query($query_limit_rscompanies1, $connMISDS) or die(mysql_error());
$row_rscompanies1 = mysql_fetch_assoc($rscompanies1);

if (isset($_GET['totalRows_rscompanies1'])) {
  $totalRows_rscompanies1 = $_GET['totalRows_rscompanies1'];
} else {
  $all_rscompanies1 = mysql_query($query_rscompanies1);
  $totalRows_rscompanies1 = mysql_num_rows($all_rscompanies1);
}
$totalPages_rscompanies1 = ceil($totalRows_rscompanies1/$maxRows_rscompanies1)-1;
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

$nav_listcompanies2->checkBoundries();

// Get the transaction recordset
$rscustom = $tNGs->getRecordset("custom");
$row_rscustom = mysql_fetch_assoc($rscustom);
$totalRows_rscustom = mysql_num_rows($rscustom);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Super User Companies</title>
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
  .KT_col_companyname {width:210px; overflow:hidden;}
  .KT_col_companynumber {width:140px; overflow:hidden;}
  .KT_col_companyemail {width:210px; overflow:hidden;}
/* 
body {
	background: #DDDDDD;
	
	}  
  
  
a { 
	color:#000;
	text-decoration: none;
	font-size: 12px;
}  

tbody { 
	text-align: left;
	}
.KT_tnglist {
	border-radius: 10px;
	padding: 2px;
	margin: auto;
	}  
	
.KT_tng h1 {
	font-family: Times New Roman;
	font-size: 20px;
	text-align: left;
	font-weight: normal;
	text-align: center;
	
	}  
	
.KT_tnglist th {
	background-color: #ea6153;
	color: #FFF;
	
	}  
  
*/
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

<p class="header">MSIDS COMPANIES - SUPER USER</p>
<div class="KT_tng" id="listcompanies2">
  <h1> Companies
    <?php
  $nav_listcompanies2->Prepare();
  require("../includes/nav/NAV_Text_Statistics.inc.php");
?>
  </h1>
  <div class="KT_tnglist">
    <form action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>" method="post" id="form1">
      <div class="KT_options"> <a href="<?php echo $nav_listcompanies2->getShowAllLink(); ?>"><?php echo NXT_getResource("Show"); ?>
	        <?php 
  // Show IF Conditional region1
  if (@$_GET['show_all_nav_listcompanies2'] == 1) {
?>
          <?php echo $_SESSION['default_max_rows_nav_listcompanies2']; ?>
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
  if (@$_SESSION['has_filter_tfi_listcompanies2'] == 1) {
?>
          <a href="<?php echo $tfi_listcompanies2->getResetFilterLink(); ?>"><?php echo NXT_getResource("Reset filter"); ?></a>
          <?php 
  // else Conditional region2
  } else { ?>
          <a href="<?php echo $tfi_listcompanies2->getShowFilterLink(); ?>"><?php echo NXT_getResource("Show filter"); ?></a>
          <?php } 
  // endif Conditional region2
?>
      </div>
      <table cellpadding="2" cellspacing="0" class="KT_tngtable">
        <thead>
          <tr class="KT_row_order">
            <th> <input type="checkbox" name="KT_selAll" id="KT_selAll"/>
            </th>
            <th id="companyname" class="KT_sorter KT_col_companyname <?php echo $tso_listcompanies2->getSortIcon('companies.companyname'); ?>"> <a href="<?php echo $tso_listcompanies2->getSortLink('companies.companyname'); ?>">Name</a></th>
            <th id="companynumber" class="KT_sorter KT_col_companynumber <?php echo $tso_listcompanies2->getSortIcon('companies.companynumber'); ?>"> <a href="<?php echo $tso_listcompanies2->getSortLink('companies.companynumber'); ?>">Number</a></th>
            <th id="companyemail" class="KT_sorter KT_col_companyemail <?php echo $tso_listcompanies2->getSortIcon('companies.companyemail'); ?>"> <a href="<?php echo $tso_listcompanies2->getSortLink('companies.companyemail'); ?>">Email</a></th>
            <th>&nbsp;</th>
          </tr>
          <?php 
  // Show IF Conditional region3
  if (@$_SESSION['has_filter_tfi_listcompanies2'] == 1) {
?>
            <tr class="KT_row_filter">
              <td>&nbsp;</td>
              <td><input type="text" name="tfi_listcompanies2_companyname" id="tfi_listcompanies2_companyname" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listcompanies2_companyname']); ?>" size="30" maxlength="100" /></td>
              <td><input type="text" name="tfi_listcompanies2_companynumber" id="tfi_listcompanies2_companynumber" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listcompanies2_companynumber']); ?>" size="20" maxlength="100" /></td>
              <td><input type="text" name="tfi_listcompanies2_companyemail" id="tfi_listcompanies2_companyemail" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listcompanies2_companyemail']); ?>" size="30" maxlength="100" /></td>
              <td><input type="submit" name="tfi_listcompanies2" value="<?php echo NXT_getResource("Filter"); ?>" /></td>
            </tr>
            <?php } 
  // endif Conditional region3
?>
        </thead>
        <tbody>
          <?php if ($totalRows_rscompanies1 == 0) { // Show if recordset empty ?>
            <tr>
              <td colspan="5"><?php echo NXT_getResource("The table is empty or the filter you've selected is too restrictive."); ?></td>
            </tr>
            <?php } // Show if recordset empty ?>
          <?php if ($totalRows_rscompanies1 > 0) { // Show if recordset not empty ?>
            <?php do { ?>
              <tr class="<?php echo @$cnt1++%2==0 ? "" : "KT_even"; ?>">
                <td><input type="checkbox" name="kt_pk_companies" class="id_checkbox" value="<?php echo $row_rscompanies1['companyname']; ?>" />
                  <input type="hidden" name="companyname" class="id_field" value="<?php echo $row_rscompanies1['companyname']; ?>" /></td>
                <td><div class="KT_col_companyname"><?php echo KT_FormatForList($row_rscompanies1['companyname'], 30); ?></div></td>
                <td><div class="KT_col_companynumber"><?php echo KT_FormatForList($row_rscompanies1['companynumber'], 20); ?></div></td>
                <td><div class="KT_col_companyemail"><?php echo KT_FormatForList($row_rscompanies1['companyemail'], 30); ?></div></td>
                <td><a class="KT_edit_link" href="edit-companies.php?companyname=<?php echo $row_rscompanies1['companyname']; ?>&amp;KT_back=1"><?php echo NXT_getResource("edit_one"); ?></a> <a class="KT_delete_link" href="#delete"><?php echo NXT_getResource("delete_one"); ?></a></td>
              </tr>
              <?php } while ($row_rscompanies1 = mysql_fetch_assoc($rscompanies1)); ?>
            <?php } // Show if recordset not empty ?>
        </tbody>
      </table>
      <div class="KT_bottomnav">
        <div>
          <?php
            $nav_listcompanies2->Prepare();
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
        <a class="KT_additem_op_link" href="edit-companies.php?KT_back=1" onclick="return nxt_list_additem(this)"><?php echo NXT_getResource("add new"); ?></a></div>
    </form>
  </div>
  <br class="clearfixplain" />
</div>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($rscompanies1);
?>
