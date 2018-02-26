<?php require_once('../Connections/connMISDS.php'); ?>
<?php
//MX Widgets3 include
require_once('../includes/wdg/WDG.php');

// Load the common classes
require_once('../includes/common/KT_common.php');

// Load the tNG classes
require_once('../includes/tng/tNG.inc.php');

// Load the KT_back class
require_once('../includes/nxt/KT_back.php');

// Make a transaction dispatcher instance
$tNGs = new tNG_dispatcher("../");

// Make unified connection variable
$conn_connMISDS = new KT_connection($connMISDS, $database_connMISDS);

//Start Restrict Access To Page
$restrict = new tNG_RestrictAccess($conn_connMISDS, "../");
//Grand Levels: Level
$restrict->addLevel("1");
$restrict->addLevel("2");
$restrict->addLevel("3");
$restrict->Execute();
//End Restrict Access To Page

// Start trigger
$formValidation = new tNG_FormValidation();
$formValidation->addField("companyname", true, "text", "", "", "", "");
$formValidation->addField("companynumber", true, "numeric", "", "", "", "");
$formValidation->addField("companyemail", true, "text", "email", "", "", "");
$tNGs->prepareValidation($formValidation);
// End trigger

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

$colname_rsUser = "-1";
if (isset($_SESSION['kt_login_id'])) {
  $colname_rsUser = $_SESSION['kt_login_id'];
}
mysql_select_db($database_connMISDS, $connMISDS);
$query_rsUser = sprintf("SELECT * FROM login_admin WHERE logid = %s", GetSQLValueString($colname_rsUser, "int"));
$rsUser = mysql_query($query_rsUser, $connMISDS) or die(mysql_error());
$row_rsUser = mysql_fetch_assoc($rsUser);
$totalRows_rsUser = mysql_num_rows($rsUser);

// Make a logout transaction instance
$logoutTransaction = new tNG_logoutTransaction($conn_connMISDS);
$tNGs->addTransaction($logoutTransaction);
// Register triggers
$logoutTransaction->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "GET", "KT_logout_now");
$logoutTransaction->registerTrigger("END", "Trigger_Default_Redirect", 99, "../login.php");
// Add columns
// End of logout transaction instance

// Make an insert transaction instance
$ins_companies = new tNG_multipleInsert($conn_connMISDS);
$tNGs->addTransaction($ins_companies);
// Register triggers
$ins_companies->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Insert1");
$ins_companies->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$ins_companies->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
// Add columns
$ins_companies->setTable("companies");
$ins_companies->addColumn("companyname", "STRING_TYPE", "POST", "companyname");
$ins_companies->addColumn("companynumber", "NUMERIC_TYPE", "POST", "companynumber");
$ins_companies->addColumn("companyemail", "STRING_TYPE", "POST", "companyemail");
$ins_companies->setPrimaryKey("companyid", "NUMERIC_TYPE");

// Make an update transaction instance
$upd_companies = new tNG_multipleUpdate($conn_connMISDS);
$tNGs->addTransaction($upd_companies);
// Register triggers
$upd_companies->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Update1");
$upd_companies->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$upd_companies->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
// Add columns
$upd_companies->setTable("companies");
$upd_companies->addColumn("companyname", "STRING_TYPE", "POST", "companyname");
$upd_companies->addColumn("companynumber", "NUMERIC_TYPE", "POST", "companynumber");
$upd_companies->addColumn("companyemail", "STRING_TYPE", "POST", "companyemail");
$upd_companies->setPrimaryKey("companyid", "NUMERIC_TYPE", "GET", "companyid");

// Make an instance of the transaction object
$del_companies = new tNG_multipleDelete($conn_connMISDS);
$tNGs->addTransaction($del_companies);
// Register triggers
$del_companies->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Delete1");
$del_companies->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
// Add columns
$del_companies->setTable("companies");
$del_companies->setPrimaryKey("companyid", "NUMERIC_TYPE", "GET", "companyid");

// Execute all the registered transactions
$tNGs->executeTransactions();

// Get the transaction recordset
$rscustom = $tNGs->getRecordset("custom");
$row_rscustom = mysql_fetch_assoc($rscustom);
$totalRows_rscustom = mysql_num_rows($rscustom);

// Get the transaction recordset
$rscompanies = $tNGs->getRecordset("companies");
$row_rscompanies = mysql_fetch_assoc($rscompanies);
$totalRows_rscompanies = mysql_num_rows($rscompanies);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:wdg="http://ns.adobe.com/addt">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Company Entry Form</title>
<link href="../includes/skins/mxkollection3.css" rel="stylesheet" type="text/css" media="all" />
<script src="../includes/common/js/base.js" type="text/javascript"></script>
<script src="../includes/common/js/utility.js" type="text/javascript"></script>
<script src="../includes/skins/style.js" type="text/javascript"></script>
<?php echo $tNGs->displayValidationRules();?>
<script src="../includes/nxt/scripts/form.js" type="text/javascript"></script>
<script src="../includes/nxt/scripts/form.js.php" type="text/javascript"></script>
<script type="text/javascript">
$NXT_FORM_SETTINGS = {
  duplicate_buttons: false,
  show_as_grid: false,
  merge_down_value: false
}
</script>
<script type="text/javascript" src="../includes/wdg/classes/MXWidgets.js"></script>
<script type="text/javascript" src="../includes/wdg/classes/MXWidgets.js.php"></script>
<script type="text/javascript" src="../includes/wdg/classes/NumericInput.js"></script>
</head>

<style>
.header {
	font-family: Georgia, "Times New Roman", Times, serif;
	font-size: 18px;
	color: #00F;
	text-align: center;
	border-bottom-width: thin;
	border-bottom-style: solid;
	border-bottom-color: #7E80B4;
	margin-left: 10px;
	margin-right: 10px;
}
a:link {
	color: #000;
	text-decoration: none;
}
a:visited {
	text-decoration: none;
	color: #000;
}
a:hover {
	text-decoration: none;
}
a:active {
	text-decoration: none;
}

</style>

<body style="background-color: #DDDDDD; background-repeat:repeat-x;">

<div class="KT_tng">
  <h1 style="text-align: center; ">
    <?php 
// Show IF Conditional region1 
if (@$_GET['companyid'] == "") {
?>
      <?php echo NXT_getResource("Insert_FH"); ?>
      <?php 
// else Conditional region1
} else { ?>
      <?php echo NXT_getResource("Update_FH"); ?>
      <?php } 
// endif Conditional region1
?>
    Your Company</h1>
  <div class="KT_tngform" style="margin-left: 400px; border-radius: 50px; padding: 20px;">
    <form method="post" id="form1" action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>">
      <?php $cnt1 = 0; ?>
      <?php do { ?>
        <?php $cnt1++; ?>
        <?php 
// Show IF Conditional region1 
if (@$totalRows_rscompanies > 1) {
?>
          <h2><?php echo NXT_getResource("Record_FH"); ?> <?php echo $cnt1; ?></h2>
          <?php } 
// endif Conditional region1
?>
        <table cellpadding="2" cellspacing="0" class="KT_tngtable">
          <tr>
            <td width="144" class="KT_th"><label for="companyname_<?php echo $cnt1; ?>"><br />
            Company Name:</label></td>
            <td width="363"><p>
              <input type="text" name="companyname_<?php echo $cnt1; ?>" id="companyname_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rscompanies['companyname']); ?>" size="60" />
                <?php echo $tNGs->displayFieldHint("companyname");?> <?php echo $tNGs->displayFieldError("companies", "companyname", $cnt1); ?></p></td>
          </tr>
          <tr>
            <td class="KT_th"><label for="companynumber_<?php echo $cnt1; ?>"><br />
            Company Number:</label></td>
            <td><p>
              <input name="companynumber_<?php echo $cnt1; ?>" id="companynumber_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rscompanies['companynumber']); ?>" size="60" wdg:negatives="no" wdg:subtype="NumericInput" wdg:type="widget" wdg:floats="no" wdg:spinner="no" />
                <?php echo $tNGs->displayFieldHint("companynumber");?> <?php echo $tNGs->displayFieldError("companies", "companynumber", $cnt1); ?></p></td>
          </tr>
          <tr>
            <td class="KT_th"><label for="companyemail_<?php echo $cnt1; ?>"><br />
            Company Email:</label></td>
            <td><p>
              <input type="text" name="companyemail_<?php echo $cnt1; ?>" id="companyemail_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rscompanies['companyemail']); ?>" size="60" />
                <?php echo $tNGs->displayFieldHint("companyemail");?> <?php echo $tNGs->displayFieldError("companies", "companyemail", $cnt1); ?></p></td>
          </tr>
        </table>
        <input type="hidden" name="kt_pk_companies_<?php echo $cnt1; ?>" class="id_field" value="<?php echo KT_escapeAttribute($row_rscompanies['kt_pk_companies']); ?>" />
        <?php } while ($row_rscompanies = mysql_fetch_assoc($rscompanies)); ?>
      <div class="KT_bottombuttons" class="KT_bottombuttons" style="text-align:center;">
             <button style="margin-bottom: 10px; margin-top: 2px;"> <a href="infradevpostmodal.php">Return To Development Postings Page</a> </button>

        <div>
          <?php 
      // Show IF Conditional region1
      if (@$_GET['companyid'] == "") {
      ?>
            <input type="submit" name="KT_Insert1" id="KT_Insert1" value="<?php echo NXT_getResource("Insert_FB"); ?>" />
            <?php 
      // else Conditional region1
      } else { ?>
            <input type="submit" name="KT_Update1" value="<?php echo NXT_getResource("Update_FB"); ?>" />
            <?php }
      // endif Conditional region1
      ?>
<input type="button" name="KT_Cancel1" value="<?php echo NXT_getResource("Cancel_FB"); ?>" onclick="return UNI_navigateCancel(event, '../includes/nxt/back.php')" />
        </div>
      </div>
    </form>
  </div>
  <br class="clearfixplain" />
</div>
<p>&nbsp;</p>
</p>
</body>
</html>
<?php
mysql_free_result($rsUser);
?>
