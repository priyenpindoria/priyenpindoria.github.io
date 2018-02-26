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
$formValidation->addField("flag_category", true, "text", "", "", "", "");
$formValidation->addField("flag_title", true, "text", "", "", "", "");
$formValidation->addField("flag_reson", true, "text", "", "", "", "");
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
$query_Recordset1 = "SELECT * FROM category";
$Recordset1 = mysql_query($query_Recordset1, $connMISDS) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

// Make an insert transaction instance
$ins_flag_posts = new tNG_multipleInsert($conn_connMISDS);
$tNGs->addTransaction($ins_flag_posts);
// Register triggers
$ins_flag_posts->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Insert1");
$ins_flag_posts->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$ins_flag_posts->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
// Add columns
$ins_flag_posts->setTable("flag_posts");
$ins_flag_posts->addColumn("flag_category", "STRING_TYPE", "POST", "flag_category");
$ins_flag_posts->addColumn("flag_title", "STRING_TYPE", "POST", "flag_title");
$ins_flag_posts->addColumn("flag_reson", "STRING_TYPE", "POST", "flag_reson");
$ins_flag_posts->setPrimaryKey("flag_id", "NUMERIC_TYPE");

// Make an update transaction instance
$upd_flag_posts = new tNG_multipleUpdate($conn_connMISDS);
$tNGs->addTransaction($upd_flag_posts);
// Register triggers
$upd_flag_posts->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Update1");
$upd_flag_posts->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$upd_flag_posts->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
// Add columns
$upd_flag_posts->setTable("flag_posts");
$upd_flag_posts->addColumn("flag_category", "STRING_TYPE", "POST", "flag_category");
$upd_flag_posts->addColumn("flag_title", "STRING_TYPE", "POST", "flag_title");
$upd_flag_posts->addColumn("flag_reson", "STRING_TYPE", "POST", "flag_reson");
$upd_flag_posts->setPrimaryKey("flag_id", "NUMERIC_TYPE", "GET", "flag_id");

// Make an instance of the transaction object
$del_flag_posts = new tNG_multipleDelete($conn_connMISDS);
$tNGs->addTransaction($del_flag_posts);
// Register triggers
$del_flag_posts->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Delete1");
$del_flag_posts->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
// Add columns
$del_flag_posts->setTable("flag_posts");
$del_flag_posts->setPrimaryKey("flag_id", "NUMERIC_TYPE", "GET", "flag_id");

// Execute all the registered transactions
$tNGs->executeTransactions();

// Get the transaction recordset
$rsflag_posts = $tNGs->getRecordset("flag_posts");
$row_rsflag_posts = mysql_fetch_assoc($rsflag_posts);
$totalRows_rsflag_posts = mysql_num_rows($rsflag_posts);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Flag Posts</title>
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
.KT_tngtable input, .KT_tngtable select {font-size: 13px;}
</style>
<body>
<?php
	echo $tNGs->getErrorMsg();
?>
<div class="KT_tng">
  <h1>
    <?php 
// Show IF Conditional region1 
if (@$_GET['flag_id'] == "") {
?>
      <?php echo NXT_getResource("Insert_FH"); ?>
      <?php 
// else Conditional region1
} else { ?>
      <?php echo NXT_getResource("Update_FH"); ?>
      <?php } 
// endif Conditional region1
?>
      A Report For A Post
  </h1>
  <div class="KT_tngform">
    <form method="post" id="form1" action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>">
      <?php $cnt1 = 0; ?>
      <?php do { ?>
        <?php $cnt1++; ?>
        <?php 
// Show IF Conditional region1 
if (@$totalRows_rsflag_posts > 1) {
?>
          <h2><?php echo NXT_getResource("Record_FH"); ?> <?php echo $cnt1; ?></h2>
          <?php } 
// endif Conditional region1
?>
        <table cellpadding="2" cellspacing="0" class="KT_tngtable">
          <tr>
            <td width="159" class="KT_th"><label for="flag_category_<?php echo $cnt1; ?>"><br />
              Category:</label></td>
            <td width="341"><p>
              <select name="flag_category_<?php echo $cnt1; ?>" id="flag_category_<?php echo $cnt1; ?>">
                <option value=""><?php echo NXT_getResource("Select one..."); ?></option>
                <?php 
do {  
?>
                <option value="<?php echo $row_Recordset1['cat_nm']?>"<?php if (!(strcmp($row_Recordset1['cat_nm'], $row_rsflag_posts['flag_category']))) {echo "SELECTED";} ?>><?php echo $row_Recordset1['cat_nm']?></option>
                <?php
} while ($row_Recordset1 = mysql_fetch_assoc($Recordset1));
  $rows = mysql_num_rows($Recordset1);
  if($rows > 0) {
      mysql_data_seek($Recordset1, 0);
	  $row_Recordset1 = mysql_fetch_assoc($Recordset1);
  }
?>
              </select>
              <?php echo $tNGs->displayFieldError("flag_posts", "flag_category", $cnt1); ?></p></td>
          </tr>
          <tr>
            <td class="KT_th"><label for="flag_title_<?php echo $cnt1; ?>"><br />
              Title of Post:</label></td>
            <td><p>
              <input type="text" name="flag_title_<?php echo $cnt1; ?>" id="flag_title_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsflag_posts['flag_title']); ?>" size="32" maxlength="100" />
              <?php echo $tNGs->displayFieldHint("flag_title");?> <?php echo $tNGs->displayFieldError("flag_posts", "flag_title", $cnt1); ?></p></td>
          </tr>
          <tr>
            <td class="KT_th"><label for="flag_reson_<?php echo $cnt1; ?>"><br />
              Reason For Flagging:</label></td>
            <td><p>
              <textarea name="flag_reson_<?php echo $cnt1; ?>" id="flag_reson_<?php echo $cnt1; ?>" cols="50" rows="5"><?php echo KT_escapeAttribute($row_rsflag_posts['flag_reson']); ?></textarea>
              <?php echo $tNGs->displayFieldHint("flag_reson");?> <?php echo $tNGs->displayFieldError("flag_posts", "flag_reson", $cnt1); ?></p></td>
          </tr>
        </table>
        <input type="hidden" name="kt_pk_flag_posts_<?php echo $cnt1; ?>" class="id_field" value="<?php echo KT_escapeAttribute($row_rsflag_posts['kt_pk_flag_posts']); ?>" />
        <?php } while ($row_rsflag_posts = mysql_fetch_assoc($rsflag_posts)); ?>
      <div class="KT_bottombuttons">
        <div>
          <?php 
      // Show IF Conditional region1
      if (@$_GET['flag_id'] == "") {
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
mysql_free_result($Recordset1);
?>
