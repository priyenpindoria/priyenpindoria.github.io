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
$tfi_listlogin_admin4 = new TFI_TableFilter($conn_connMISDS, "tfi_listlogin_admin4");
$tfi_listlogin_admin4->addColumn("login_admin.firstname", "STRING_TYPE", "firstname", "%");
$tfi_listlogin_admin4->addColumn("login_admin.lastname", "STRING_TYPE", "lastname", "%");
$tfi_listlogin_admin4->addColumn("login_admin.username", "STRING_TYPE", "username", "%");
$tfi_listlogin_admin4->addColumn("login_admin.email", "STRING_TYPE", "email", "%");
$tfi_listlogin_admin4->addColumn("login_admin.password", "STRING_TYPE", "password", "%");
$tfi_listlogin_admin4->addColumn("login_admin.dob", "DATE_TYPE", "dob", "=");
$tfi_listlogin_admin4->addColumn("login_admin.levelid", "NUMERIC_TYPE", "levelid", "=");
$tfi_listlogin_admin4->Execute();

// Sorter
$tso_listlogin_admin4 = new TSO_TableSorter("rslogin_admin1", "tso_listlogin_admin4");
$tso_listlogin_admin4->addColumn("login_admin.firstname");
$tso_listlogin_admin4->addColumn("login_admin.lastname");
$tso_listlogin_admin4->addColumn("login_admin.username");
$tso_listlogin_admin4->addColumn("login_admin.email");
$tso_listlogin_admin4->addColumn("login_admin.password");
$tso_listlogin_admin4->addColumn("login_admin.dob");
$tso_listlogin_admin4->addColumn("login_admin.levelid");
$tso_listlogin_admin4->setDefault("login_admin.firstname");
$tso_listlogin_admin4->Execute();

// Navigation
$nav_listlogin_admin4 = new NAV_Regular("nav_listlogin_admin4", "rslogin_admin1", "../", $_SERVER['PHP_SELF'], 10);

mysql_select_db($database_connMISDS, $connMISDS);
$query_level_menu = "SELECT * FROM userlevels";
$level_menu = mysql_query($query_level_menu, $connMISDS) or die(mysql_error());
$row_level_menu = mysql_fetch_assoc($level_menu);
$totalRows_level_menu = mysql_num_rows($level_menu);

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
$maxRows_rslogin_admin1 = $_SESSION['max_rows_nav_listlogin_admin4'];
$pageNum_rslogin_admin1 = 0;
if (isset($_GET['pageNum_rslogin_admin1'])) {
  $pageNum_rslogin_admin1 = $_GET['pageNum_rslogin_admin1'];
}
$startRow_rslogin_admin1 = $pageNum_rslogin_admin1 * $maxRows_rslogin_admin1;

// Defining List Recordset variable
$NXTFilter_rslogin_admin1 = "1=1";
if (isset($_SESSION['filter_tfi_listlogin_admin4'])) {
  $NXTFilter_rslogin_admin1 = $_SESSION['filter_tfi_listlogin_admin4'];
}
// Defining List Recordset variable
$NXTSort_rslogin_admin1 = "login_admin.firstname";
if (isset($_SESSION['sorter_tso_listlogin_admin4'])) {
  $NXTSort_rslogin_admin1 = $_SESSION['sorter_tso_listlogin_admin4'];
}
mysql_select_db($database_connMISDS, $connMISDS);

$query_rslogin_admin1 = "SELECT login_admin.firstname, login_admin.lastname, login_admin.username, login_admin.email, login_admin.password, login_admin.dob, login_admin.levelid, login_admin.logid FROM login_admin WHERE {$NXTFilter_rslogin_admin1} ORDER BY {$NXTSort_rslogin_admin1}";
$query_limit_rslogin_admin1 = sprintf("%s LIMIT %d, %d", $query_rslogin_admin1, $startRow_rslogin_admin1, $maxRows_rslogin_admin1);
$rslogin_admin1 = mysql_query($query_limit_rslogin_admin1, $connMISDS) or die(mysql_error());
$row_rslogin_admin1 = mysql_fetch_assoc($rslogin_admin1);

if (isset($_GET['totalRows_rslogin_admin1'])) {
  $totalRows_rslogin_admin1 = $_GET['totalRows_rslogin_admin1'];
} else {
  $all_rslogin_admin1 = mysql_query($query_rslogin_admin1);
  $totalRows_rslogin_admin1 = mysql_num_rows($all_rslogin_admin1);
}
$totalPages_rslogin_admin1 = ceil($totalRows_rslogin_admin1/$maxRows_rslogin_admin1)-1;
//End NeXTenesio3 Special List Recordset

$nav_listlogin_admin4->checkBoundries();

// Get the transaction recordset
$rscustom = $tNGs->getRecordset("custom");
$row_rscustom = mysql_fetch_assoc($rscustom);
$totalRows_rscustom = mysql_num_rows($rscustom);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Super User User</title>
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
  .KT_col_firstname {width:140px; overflow:hidden;}
  .KT_col_lastname {width:140px; overflow:hidden;}
  .KT_col_username {width:140px; overflow:hidden;}
  .KT_col_email {width:140px; overflow:hidden;}
  .KT_col_password {width:140px; overflow:hidden;}
  .KT_col_dob {width:140px; overflow:hidden;}
  .KT_col_levelid {width:140px; overflow:hidden;}
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
</head>
<link rel="stylesheet" type="text/css" href="css_list_modal.css" />
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

<p class="header">MSIDS USERS - SUPER USER</p>
<div class="KT_tng" id="listlogin_admin4">
  <h1> A User
    <?php
  $nav_listlogin_admin4->Prepare();
  require("../includes/nav/NAV_Text_Statistics.inc.php");
?>
  </h1>
  <div class="KT_tnglist">
    <form action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>" method="post" id="form1">
      <div class="KT_options"> <a href="<?php echo $nav_listlogin_admin4->getShowAllLink(); ?>"><?php echo NXT_getResource("Show"); ?>
        <?php 
  // Show IF Conditional region1
  if (@$_GET['show_all_nav_listlogin_admin4'] == 1) {
?>
          <?php echo $_SESSION['default_max_rows_nav_listlogin_admin4']; ?>
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
  if (@$_SESSION['has_filter_tfi_listlogin_admin4'] == 1) {
?>
          <a href="<?php echo $tfi_listlogin_admin4->getResetFilterLink(); ?>"><?php echo NXT_getResource("Reset filter"); ?></a>
          <?php 
  // else Conditional region2
  } else { ?>
          <a href="<?php echo $tfi_listlogin_admin4->getShowFilterLink(); ?>"><?php echo NXT_getResource("Show filter"); ?></a>
          <?php } 
  // endif Conditional region2
?>
      </div>
      <table cellpadding="2" cellspacing="0" class="KT_tngtable">
        <thead>
          <tr class="KT_row_order">
            <th> <input type="checkbox" name="KT_selAll" id="KT_selAll"/>
            </th>
            <th id="firstname" class="KT_sorter KT_col_firstname <?php echo $tso_listlogin_admin4->getSortIcon('login_admin.firstname'); ?>"> <a href="<?php echo $tso_listlogin_admin4->getSortLink('login_admin.firstname'); ?>">First Name</a></th>
            <th id="lastname" class="KT_sorter KT_col_lastname <?php echo $tso_listlogin_admin4->getSortIcon('login_admin.lastname'); ?>"> <a href="<?php echo $tso_listlogin_admin4->getSortLink('login_admin.lastname'); ?>">Surname</a></th>
            <th id="username" class="KT_sorter KT_col_username <?php echo $tso_listlogin_admin4->getSortIcon('login_admin.username'); ?>"> <a href="<?php echo $tso_listlogin_admin4->getSortLink('login_admin.username'); ?>">Username</a></th>
            <th id="email" class="KT_sorter KT_col_email <?php echo $tso_listlogin_admin4->getSortIcon('login_admin.email'); ?>"> <a href="<?php echo $tso_listlogin_admin4->getSortLink('login_admin.email'); ?>">Email</a></th>
          
            <th id="dob" class="KT_sorter KT_col_dob <?php echo $tso_listlogin_admin4->getSortIcon('login_admin.dob'); ?>"> <a href="<?php echo $tso_listlogin_admin4->getSortLink('login_admin.dob'); ?>">Date of Birth</a></th>
            <th id="levelid" class="KT_sorter KT_col_levelid <?php echo $tso_listlogin_admin4->getSortIcon('login_admin.levelid'); ?>"> <a href="<?php echo $tso_listlogin_admin4->getSortLink('login_admin.levelid'); ?>">Level ID</a></th>
            <th>&nbsp;</th>
          </tr>
          <?php 
  // Show IF Conditional region3
  if (@$_SESSION['has_filter_tfi_listlogin_admin4'] == 1) {
?>
            <tr class="KT_row_filter">
              <td>&nbsp;</td>
              <td><input type="text" name="tfi_listlogin_admin4_firstname" id="tfi_listlogin_admin4_firstname" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listlogin_admin4_firstname']); ?>" size="20" maxlength="100" /></td>
              <td><input type="text" name="tfi_listlogin_admin4_lastname" id="tfi_listlogin_admin4_lastname" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listlogin_admin4_lastname']); ?>" size="20" maxlength="100" /></td>
              <td><input type="text" name="tfi_listlogin_admin4_username" id="tfi_listlogin_admin4_username" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listlogin_admin4_username']); ?>" size="20" maxlength="100" /></td>
              <td><input type="text" name="tfi_listlogin_admin4_email" id="tfi_listlogin_admin4_email" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listlogin_admin4_email']); ?>" size="20" maxlength="100" /></td>
              <td><input type="text" name="tfi_listlogin_admin4_password" id="tfi_listlogin_admin4_password" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listlogin_admin4_password']); ?>" size="20" maxlength="100" /></td>
              <td><input type="text" name="tfi_listlogin_admin4_dob" id="tfi_listlogin_admin4_dob" value="<?php echo @$_SESSION['tfi_listlogin_admin4_dob']; ?>" size="10" maxlength="22" /></td>
              <td><select name="tfi_listlogin_admin4_levelid" id="tfi_listlogin_admin4_levelid">
                <option value="" <?php if (!(strcmp("", @$_SESSION['tfi_listlogin_admin4_levelid']))) {echo "SELECTED";} ?>><?php echo NXT_getResource("None"); ?></option>
                <?php
do {  
?>
                <option value="<?php echo $row_level_menu['levelid']?>"<?php if (!(strcmp($row_level_menu['levelid'], @$_SESSION['tfi_listlogin_admin4_levelid']))) {echo "SELECTED";} ?>><?php echo $row_level_menu['level']?></option>
                <?php
} while ($row_level_menu = mysql_fetch_assoc($level_menu));
  $rows = mysql_num_rows($level_menu);
  if($rows > 0) {
      mysql_data_seek($level_menu, 0);
	  $row_level_menu = mysql_fetch_assoc($level_menu);
  }
?>
              </select></td>
              <td><input type="submit" name="tfi_listlogin_admin4" value="<?php echo NXT_getResource("Filter"); ?>" /></td>
            </tr>
            <?php } 
  // endif Conditional region3
?>
        </thead>
        <tbody>
          <?php if ($totalRows_rslogin_admin1 == 0) { // Show if recordset empty ?>
            <tr>
              <td colspan="9"><?php echo NXT_getResource("The table is empty or the filter you've selected is too restrictive."); ?></td>
            </tr>
            <?php } // Show if recordset empty ?>
          <?php if ($totalRows_rslogin_admin1 > 0) { // Show if recordset not empty ?>
            <?php do { ?>
              <tr class="<?php echo @$cnt1++%2==0 ? "" : "KT_even"; ?>">
                <td><input type="checkbox" name="kt_pk_login_admin" class="id_checkbox" value="<?php echo $row_rslogin_admin1['logid']; ?>" />
                  <input type="hidden" name="logid" class="id_field" value="<?php echo $row_rslogin_admin1['logid']; ?>" /></td>
                <td><div class="KT_col_firstname"><?php echo KT_FormatForList($row_rslogin_admin1['firstname'], 20); ?></div></td>
                <td><div class="KT_col_lastname"><?php echo KT_FormatForList($row_rslogin_admin1['lastname'], 20); ?></div></td>
                <td><div class="KT_col_username"><?php echo KT_FormatForList($row_rslogin_admin1['username'], 20); ?></div></td>
                <td><div class="KT_col_email"><?php echo KT_FormatForList($row_rslogin_admin1['email'], 20); ?></div></td>
               
                <td><div class="KT_col_dob"><?php echo KT_formatDate($row_rslogin_admin1['dob']); ?></div></td>
                <td><div class="KT_col_levelid"><?php echo KT_FormatForList($row_rslogin_admin1['levelid'], 20); ?></div></td>
                <td><a class="KT_edit_link" href="add-users.php?logid=<?php echo $row_rslogin_admin1['logid']; ?>&amp;KT_back=1"><?php echo NXT_getResource("edit_one"); ?></a> <a class="KT_delete_link" href="#delete"><?php echo NXT_getResource("delete_one"); ?></a></td>
              </tr>
              <?php } while ($row_rslogin_admin1 = mysql_fetch_assoc($rslogin_admin1)); ?>
            <?php } // Show if recordset not empty ?>
        </tbody>
      </table>
      <div class="KT_bottomnav">
        <div>
          <?php
            $nav_listlogin_admin4->Prepare();
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
        <a class="KT_additem_op_link" href="add-users.php?KT_back=1" onclick="return nxt_list_additem(this)"><?php echo NXT_getResource("add new"); ?></a></div>
    </form>
  </div>
  <br class="clearfixplain" />
</div>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($level_menu);

mysql_free_result($rslogin_admin1);
?>
