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
$formValidation->addField("educationid", true, "text", "", "", "", "");
$formValidation->addField("educname", true, "text", "", "", "", "");
$formValidation->addField("educdesc", true, "text", "", "", "", "");
$formValidation->addField("educloca", true, "text", "", "", "", "");
$formValidation->addField("educdate", true, "date", "date", "", "", "");
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
$query_subsector = "SELECT * FROM education_subsector";
$subsector = mysql_query($query_subsector, $connMISDS) or die(mysql_error());
$row_subsector = mysql_fetch_assoc($subsector);
$totalRows_subsector = mysql_num_rows($subsector);

// Make an insert transaction instance
$ins_education = new tNG_multipleInsert($conn_connMISDS);
$tNGs->addTransaction($ins_education);
// Register triggers
$ins_education->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Insert1");
$ins_education->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$ins_education->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
// Add columns
$ins_education->setTable("education");
$ins_education->addColumn("educationid", "STRING_TYPE", "POST", "educationid");
$ins_education->addColumn("educname", "STRING_TYPE", "POST", "educname");
$ins_education->addColumn("educdesc", "STRING_TYPE", "POST", "educdesc");
$ins_education->addColumn("educloca", "STRING_TYPE", "POST", "educloca");
$ins_education->addColumn("educdate", "DATE_TYPE", "POST", "educdate");
$ins_education->setPrimaryKey("educid", "NUMERIC_TYPE");

// Make an update transaction instance
$upd_education = new tNG_multipleUpdate($conn_connMISDS);
$tNGs->addTransaction($upd_education);
// Register triggers
$upd_education->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Update1");
$upd_education->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$upd_education->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
// Add columns
$upd_education->setTable("education");
$upd_education->addColumn("educationid", "STRING_TYPE", "POST", "educationid");
$upd_education->addColumn("educname", "STRING_TYPE", "POST", "educname");
$upd_education->addColumn("educdesc", "STRING_TYPE", "POST", "educdesc");
$upd_education->addColumn("educloca", "STRING_TYPE", "POST", "educloca");
$upd_education->addColumn("educdate", "DATE_TYPE", "POST", "educdate");
$upd_education->setPrimaryKey("educid", "NUMERIC_TYPE", "GET", "educid");

// Make an instance of the transaction object
$del_education = new tNG_multipleDelete($conn_connMISDS);
$tNGs->addTransaction($del_education);
// Register triggers
$del_education->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Delete1");
$del_education->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
// Add columns
$del_education->setTable("education");
$del_education->setPrimaryKey("educid", "NUMERIC_TYPE", "GET", "educid");

// Execute all the registered transactions
$tNGs->executeTransactions();

// Get the transaction recordset
$rseducation = $tNGs->getRecordset("education");
$row_rseducation = mysql_fetch_assoc($rseducation);
$totalRows_rseducation = mysql_num_rows($rseducation);
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
</style>


<body style="background-color: #DDDDDD; background-repeat:repeat-x;">
<?php
	echo $tNGs->getErrorMsg();
?>
<div class="KT_tng">
  <h1 style="text-align: center; ">
    <?php 
// Show IF Conditional region1 
if (@$_GET['educid'] == "") {
?>
      <?php echo NXT_getResource("Insert_FH"); ?>
      <?php 
// else Conditional region1
} else { ?>
      <?php echo NXT_getResource("Update_FH"); ?>
      <?php } 
// endif Conditional region1
?>
    An Education Post</h1>
  <div class="KT_tngform" style="margin-left: 400px; border-radius: 50px; padding: 20px;">
    <form method="post" id="form1" action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>">
      <?php $cnt1 = 0; ?>
      <?php do { ?>
        <?php $cnt1++; ?>
        <?php 
// Show IF Conditional region1 
if (@$totalRows_rseducation > 1) {
?>
          <h2><?php echo NXT_getResource("Record_FH"); ?> <?php echo $cnt1; ?></h2>
          <?php } 
// endif Conditional region1
?>
        <table cellpadding="2" cellspacing="0" class="KT_tngtable">
          <tr>
            <td width="143" class="KT_th"><label for="educationid_<?php echo $cnt1; ?>"><br />
              Sector:</label></td>
            <td width="363"><p>
              <select name="educationid_<?php echo $cnt1; ?>" id="educationid_<?php echo $cnt1; ?>">
                <option value=""><?php echo NXT_getResource("Select one..."); ?></option>
                <?php 
do {  
?>
                <option value="<?php echo $row_subsector['educationname']?>"<?php if (!(strcmp($row_subsector['educationname'], $row_rseducation['educationid']))) {echo "SELECTED";} ?>><?php echo $row_subsector['educationname']?></option>
                <?php
} while ($row_subsector = mysql_fetch_assoc($subsector));
  $rows = mysql_num_rows($subsector);
  if($rows > 0) {
      mysql_data_seek($subsector, 0);
	  $row_subsector = mysql_fetch_assoc($subsector);
  }
?>
              </select>
                <?php echo $tNGs->displayFieldError("education", "educationid", $cnt1); ?></p></td>
          </tr>
          <tr>
            <td class="KT_th"><label for="educname_<?php echo $cnt1; ?>"><br />
              Title:</label></td>
            <td><p>
              <input type="text" name="educname_<?php echo $cnt1; ?>" id="educname_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rseducation['educname']); ?>" size="32" maxlength="100" />
                <?php echo $tNGs->displayFieldHint("educname");?> <?php echo $tNGs->displayFieldError("education", "educname", $cnt1); ?></p></td>
          </tr>
          <tr>
            <td class="KT_th"><label for="educdesc_<?php echo $cnt1; ?>"><br />
              Description:</label></td>
            <td><p>
              <textarea name="educdesc_<?php echo $cnt1; ?>" id="educdesc_<?php echo $cnt1; ?>" cols="50" rows="5"><?php echo KT_escapeAttribute($row_rseducation['educdesc']); ?></textarea>
                <?php echo $tNGs->displayFieldHint("educdesc");?> <?php echo $tNGs->displayFieldError("education", "educdesc", $cnt1); ?></p></td>
          </tr>
          <tr>
            <td class="KT_th"><label for="educloca_<?php echo $cnt1; ?>"><br />
              Location:</label></td>
            <td><p>
              <input type="text" name="educloca_<?php echo $cnt1; ?>" id="educloca_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rseducation['educloca']); ?>" size="32" maxlength="100" />
                <?php echo $tNGs->displayFieldHint("educloca");?> <?php echo $tNGs->displayFieldError("education", "educloca", $cnt1); ?></p></td>
          </tr>
          <tr>
            <td class="KT_th"><label for="educdate_<?php echo $cnt1; ?>"><br />
              Date:</label></td>
            <td><p>
              <input type="text" name="educdate_<?php echo $cnt1; ?>" id="educdate_<?php echo $cnt1; ?>" value="<?php echo KT_formatDate($row_rseducation['educdate']); ?>" size="10" maxlength="22" />
                <?php echo $tNGs->displayFieldHint("educdate");?> <?php echo $tNGs->displayFieldError("education", "educdate", $cnt1); ?></p></td>
          </tr>
        </table>
        <input type="hidden" name="kt_pk_education_<?php echo $cnt1; ?>" class="id_field" value="<?php echo KT_escapeAttribute($row_rseducation['kt_pk_education']); ?>" />
        <?php } while ($row_rseducation = mysql_fetch_assoc($rseducation)); ?>
      <div class="KT_bottombuttons"  style="text-align:center;">
  <button style="margin-bottom: 10px; margin-top: 2px;"> <a href="edumodal.php">Return To Education Page</a> </button>

        <div>
          <?php 
      // Show IF Conditional region1
      if (@$_GET['educid'] == "") {
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
