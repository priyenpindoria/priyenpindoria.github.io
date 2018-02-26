<?php require_once('../Connections/connMISDS.php'); ?>
<?php
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

// Start trigger
$formValidation = new tNG_FormValidation();
$formValidation->addField("envsubsec", true, "text", "", "", "", "");
$formValidation->addField("environname", true, "text", "", "", "", "");
$formValidation->addField("environdesc", true, "text", "", "", "", "");
$formValidation->addField("environloca", true, "text", "", "", "", "");
$formValidation->addField("environdate", true, "date", "date", "", "", "");
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

mysql_select_db($database_connMISDS, $connMISDS);
$query_subsector = "SELECT * FROM env_subsector";
$subsector = mysql_query($query_subsector, $connMISDS) or die(mysql_error());
$row_subsector = mysql_fetch_assoc($subsector);
$totalRows_subsector = mysql_num_rows($subsector);

// Make an insert transaction instance
$ins_environment = new tNG_multipleInsert($conn_connMISDS);
$tNGs->addTransaction($ins_environment);
// Register triggers
$ins_environment->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Insert1");
$ins_environment->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$ins_environment->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
// Add columns
$ins_environment->setTable("environment");
$ins_environment->addColumn("envsubsec", "STRING_TYPE", "POST", "envsubsec");
$ins_environment->addColumn("environname", "STRING_TYPE", "POST", "environname");
$ins_environment->addColumn("environdesc", "STRING_TYPE", "POST", "environdesc");
$ins_environment->addColumn("environloca", "STRING_TYPE", "POST", "environloca");
$ins_environment->addColumn("environdate", "DATE_TYPE", "POST", "environdate");
$ins_environment->setPrimaryKey("environid", "NUMERIC_TYPE");

// Make an update transaction instance
$upd_environment = new tNG_multipleUpdate($conn_connMISDS);
$tNGs->addTransaction($upd_environment);
// Register triggers
$upd_environment->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Update1");
$upd_environment->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$upd_environment->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
// Add columns
$upd_environment->setTable("environment");
$upd_environment->addColumn("envsubsec", "STRING_TYPE", "POST", "envsubsec");
$upd_environment->addColumn("environname", "STRING_TYPE", "POST", "environname");
$upd_environment->addColumn("environdesc", "STRING_TYPE", "POST", "environdesc");
$upd_environment->addColumn("environloca", "STRING_TYPE", "POST", "environloca");
$upd_environment->addColumn("environdate", "DATE_TYPE", "POST", "environdate");
$upd_environment->setPrimaryKey("environid", "NUMERIC_TYPE", "GET", "environid");

// Make an instance of the transaction object
$del_environment = new tNG_multipleDelete($conn_connMISDS);
$tNGs->addTransaction($del_environment);
// Register triggers
$del_environment->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Delete1");
$del_environment->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
// Add columns
$del_environment->setTable("environment");
$del_environment->setPrimaryKey("environid", "NUMERIC_TYPE", "GET", "environid");

// Execute all the registered transactions
$tNGs->executeTransactions();

// Get the transaction recordset
$rsenvironment = $tNGs->getRecordset("environment");
$row_rsenvironment = mysql_fetch_assoc($rsenvironment);
$totalRows_rsenvironment = mysql_num_rows($rsenvironment);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
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

body {
	background-color: #DDDDDD;
	background-repeat: repeat-x;

}

h1 {
	text-align: center;

}

.KT_tngform {
	margin-left: 400px;
	border-radius: 50px;
	padding: 20px;
}

.KT_bottombuttons {
	text-align: center;

	}

</style>

<body>
<?php
	echo $tNGs->getErrorMsg();
?>
<div class="KT_tng">
  <h1>
    <?php 
// Show IF Conditional region1 
if (@$_GET['environid'] == "") {
?>
      <?php echo NXT_getResource("Insert_FH"); ?>
      <?php 
// else Conditional region1
} else { ?>
      <?php echo NXT_getResource("Update_FH"); ?>
      <?php } 
// endif Conditional region1
?>
    Environment </h1>
  <div class="KT_tngform">
    <form method="post" id="form1" action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>">
      <?php $cnt1 = 0; ?>
      <?php do { ?>
        <?php $cnt1++; ?>
        <?php 
// Show IF Conditional region1 
if (@$totalRows_rsenvironment > 1) {
?>
          <h2><?php echo NXT_getResource("Record_FH"); ?> <?php echo $cnt1; ?></h2>
          <?php } 
// endif Conditional region1
?>
        <table cellpadding="2" cellspacing="0" class="KT_tngtable">
          <tr>
            <td width="143" class="KT_th"><label for="envsubsec_<?php echo $cnt1; ?>"><br />
              Sub Sector:</label></td>
            <td width="363"><p>
              <select name="envsubsec_<?php echo $cnt1; ?>" id="envsubsec_<?php echo $cnt1; ?>">
                <option value=""><?php echo NXT_getResource("Select one..."); ?></option>
                <?php 
do {  
?>
                <option value="<?php echo $row_subsector['envname']?>"<?php if (!(strcmp($row_subsector['envname'], $row_rsenvironment['envsubsec']))) {echo "SELECTED";} ?>><?php echo $row_subsector['envname']?></option>
                <?php
} while ($row_subsector = mysql_fetch_assoc($subsector));
  $rows = mysql_num_rows($subsector);
  if($rows > 0) {
      mysql_data_seek($subsector, 0);
	  $row_subsector = mysql_fetch_assoc($subsector);
  }
?>
              </select>
                <?php echo $tNGs->displayFieldError("environment", "envsubsec", $cnt1); ?></p></td>
          </tr>
          <tr>
            <td class="KT_th"><label for="environname_<?php echo $cnt1; ?>"><br />
              Title:</label></td>
            <td><p>
              <input type="text" name="environname_<?php echo $cnt1; ?>" id="environname_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsenvironment['environname']); ?>" size="32" maxlength="100" />
                <?php echo $tNGs->displayFieldHint("environname");?> <?php echo $tNGs->displayFieldError("environment", "environname", $cnt1); ?></p></td>
          </tr>
          <tr>
            <td class="KT_th"><label for="environdesc_<?php echo $cnt1; ?>"><br />
              Description:</label></td>
            <td><p>
              <textarea name="environdesc_<?php echo $cnt1; ?>" id="environdesc_<?php echo $cnt1; ?>" cols="50" rows="5"><?php echo KT_escapeAttribute($row_rsenvironment['environdesc']); ?></textarea>
                <?php echo $tNGs->displayFieldHint("environdesc");?> <?php echo $tNGs->displayFieldError("environment", "environdesc", $cnt1); ?></p></td>
          </tr>
          <tr>
            <td class="KT_th"><label for="environloca_<?php echo $cnt1; ?>"><br />
              Location:</label></td>
            <td><p>
              <input type="text" name="environloca_<?php echo $cnt1; ?>" id="environloca_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsenvironment['environloca']); ?>" size="32" maxlength="100" />
                <?php echo $tNGs->displayFieldHint("environloca");?> <?php echo $tNGs->displayFieldError("environment", "environloca", $cnt1); ?></p></td>
          </tr>
          <tr>
            <td class="KT_th"><label for="environdate_<?php echo $cnt1; ?>"><br />
              Date:</label></td>
            <td><p>
              <input type="text" name="environdate_<?php echo $cnt1; ?>" id="environdate_<?php echo $cnt1; ?>" value="<?php echo KT_formatDate($row_rsenvironment['environdate']); ?>" size="10" maxlength="22" />
                <?php echo $tNGs->displayFieldHint("environdate");?> <?php echo $tNGs->displayFieldError("environment", "environdate", $cnt1); ?></p></td>
          </tr>
        </table>
        <input type="hidden" name="kt_pk_environment_<?php echo $cnt1; ?>" class="id_field" value="<?php echo KT_escapeAttribute($row_rsenvironment['kt_pk_environment']); ?>" />
        <?php } while ($row_rsenvironment = mysql_fetch_assoc($rsenvironment)); ?>
      <div class="KT_bottombuttons">
        <button style="margin-bottom: 10px; margin-top: 2px;"> <a href="envmodal.php">Return To Environment Page</a> </button>

        <div>
          <?php 
      // Show IF Conditional region1
      if (@$_GET['environid'] == "") {
      ?>
            <input type="submit" name="KT_Insert1" id="KT_Insert1" value="<?php echo NXT_getResource("Insert_FB"); ?>" />
            <?php 
      // else Conditional region1
      } else { ?>
            <input type="submit" name="KT_Update1" value="<?php echo NXT_getResource("Update_FB"); ?>" />
            <input type="submit" name="KT_Delete1" value="<?php echo NXT_getResource("Delete_FB"); ?>" onclick="return confirm('<?php echo NXT_getResource("Are you sure?"); ?>');" />
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
</body>
</html>
<?php
mysql_free_result($subsector);
?>
