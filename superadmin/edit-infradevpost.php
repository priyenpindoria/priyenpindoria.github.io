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
$formValidation->addField("devname", true, "text", "", "", "", "");
$formValidation->addField("devdesc", true, "text", "", "", "", "");
$formValidation->addField("devcompany", true, "text", "", "", "", "");
$formValidation->addField("devnumber", true, "numeric", "int", "", "", "");
$formValidation->addField("devemail", true, "text", "email", "", "", "");
$formValidation->addField("devlocation", true, "text", "", "", "", "");
$formValidation->addField("devstdate", true, "date", "date", "", "", "");
$formValidation->addField("devduedate", true, "date", "date", "", "", "");
$tNGs->prepareValidation($formValidation);
// End trigger

// Make an insert transaction instance
$ins_infradev_post = new tNG_multipleInsert($conn_connMISDS);
$tNGs->addTransaction($ins_infradev_post);
// Register triggers
$ins_infradev_post->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Insert1");
$ins_infradev_post->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$ins_infradev_post->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
// Add columns
$ins_infradev_post->setTable("infradev_post");
$ins_infradev_post->addColumn("devname", "STRING_TYPE", "POST", "devname");
$ins_infradev_post->addColumn("devdesc", "STRING_TYPE", "POST", "devdesc");
$ins_infradev_post->addColumn("devcompany", "STRING_TYPE", "POST", "devcompany");
$ins_infradev_post->addColumn("devnumber", "NUMERIC_TYPE", "POST", "devnumber");
$ins_infradev_post->addColumn("devemail", "STRING_TYPE", "POST", "devemail");
$ins_infradev_post->addColumn("devlocation", "STRING_TYPE", "POST", "devlocation");
$ins_infradev_post->addColumn("devstdate", "DATE_TYPE", "POST", "devstdate");
$ins_infradev_post->addColumn("devduedate", "DATE_TYPE", "POST", "devduedate");
$ins_infradev_post->setPrimaryKey("devid", "NUMERIC_TYPE");

// Make an update transaction instance
$upd_infradev_post = new tNG_multipleUpdate($conn_connMISDS);
$tNGs->addTransaction($upd_infradev_post);
// Register triggers
$upd_infradev_post->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Update1");
$upd_infradev_post->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$upd_infradev_post->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
// Add columns
$upd_infradev_post->setTable("infradev_post");
$upd_infradev_post->addColumn("devname", "STRING_TYPE", "POST", "devname");
$upd_infradev_post->addColumn("devdesc", "STRING_TYPE", "POST", "devdesc");
$upd_infradev_post->addColumn("devcompany", "STRING_TYPE", "POST", "devcompany");
$upd_infradev_post->addColumn("devnumber", "NUMERIC_TYPE", "POST", "devnumber");
$upd_infradev_post->addColumn("devemail", "STRING_TYPE", "POST", "devemail");
$upd_infradev_post->addColumn("devlocation", "STRING_TYPE", "POST", "devlocation");
$upd_infradev_post->addColumn("devstdate", "DATE_TYPE", "POST", "devstdate");
$upd_infradev_post->addColumn("devduedate", "DATE_TYPE", "POST", "devduedate");
$upd_infradev_post->setPrimaryKey("devid", "NUMERIC_TYPE", "GET", "devid");

// Make an instance of the transaction object
$del_infradev_post = new tNG_multipleDelete($conn_connMISDS);
$tNGs->addTransaction($del_infradev_post);
// Register triggers
$del_infradev_post->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Delete1");
$del_infradev_post->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
// Add columns
$del_infradev_post->setTable("infradev_post");
$del_infradev_post->setPrimaryKey("devid", "NUMERIC_TYPE", "GET", "devid");

// Execute all the registered transactions
$tNGs->executeTransactions();

// Get the transaction recordset
$rsinfradev_post = $tNGs->getRecordset("infradev_post");
$row_rsinfradev_post = mysql_fetch_assoc($rsinfradev_post);
$totalRows_rsinfradev_post = mysql_num_rows($rsinfradev_post);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>INSERT PROJECT</title>
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
<p class="header"><strong>MSIDS DEVELOPMENT POSTINGS</strong></p>
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
if (@$_GET['devid'] == "") {
?>
      <?php echo NXT_getResource("Insert_FH"); ?>
      <?php 
// else Conditional region1
} else { ?>
      <?php echo NXT_getResource("Update_FH"); ?>
      <?php } 
// endif Conditional region1
?>
    A Development Project</h1>
  <div class="KT_tngform" style="margin-left: 400px; border-radius: 50px; padding: 20px;">
    <form method="post" id="form1" action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>">
      <?php $cnt1 = 0; ?>
      <?php do { ?>
        <?php $cnt1++; ?>
        <?php 
// Show IF Conditional region1 
if (@$totalRows_rsinfradev_post > 1) {
?>
          <h2><?php echo NXT_getResource("Record_FH"); ?> <?php echo $cnt1; ?></h2>
          <?php } 
// endif Conditional region1
?>
        <table cellpadding="2" cellspacing="0" class="KT_tngtable">
          <tr>
            <td width="166" class="KT_th"><label for="devname_<?php echo $cnt1; ?>"><br />
            Project Name:</label></td>
            <td width="340"><p>
              <input type="text" name="devname_<?php echo $cnt1; ?>" id="devname_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsinfradev_post['devname']); ?>" size="32" maxlength="100" />
                <?php echo $tNGs->displayFieldHint("devname");?> <?php echo $tNGs->displayFieldError("infradev_post", "devname", $cnt1); ?></p></td>
          </tr>
          <tr>
            <td class="KT_th"><label for="devdesc_<?php echo $cnt1; ?>"><br />
            Project Description:</label></td>
            <td><p>
              <textarea name="devdesc_<?php echo $cnt1; ?>" id="devdesc_<?php echo $cnt1; ?>" cols="50" rows="5"><?php echo KT_escapeAttribute($row_rsinfradev_post['devdesc']); ?></textarea>
                <?php echo $tNGs->displayFieldHint("devdesc");?> <?php echo $tNGs->displayFieldError("infradev_post", "devdesc", $cnt1); ?></p></td>
          </tr>
          <tr>
            <td class="KT_th"><label for="devcompany_<?php echo $cnt1; ?>"><br />
            Client:</label></td>
            <td><p>
              <input type="text" name="devcompany_<?php echo $cnt1; ?>" id="devcompany_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsinfradev_post['devcompany']); ?>" size="32" maxlength="100" />
                <?php echo $tNGs->displayFieldHint("devcompany");?> <?php echo $tNGs->displayFieldError("infradev_post", "devcompany", $cnt1); ?></p></td>
          </tr>
          <tr>
            <td class="KT_th"><label for="devnumber_<?php echo $cnt1; ?>"><br />
            Number:</label></td>
            <td><p>
              <input type="text" name="devnumber_<?php echo $cnt1; ?>" id="devnumber_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsinfradev_post['devnumber']); ?>" size="7" />
                <?php echo $tNGs->displayFieldHint("devnumber");?> <?php echo $tNGs->displayFieldError("infradev_post", "devnumber", $cnt1); ?></p></td>
          </tr>
          <tr>
            <td class="KT_th"><label for="devemail_<?php echo $cnt1; ?>"><br />
            Email:</label></td>
            <td><p>
              <input type="text" name="devemail_<?php echo $cnt1; ?>" id="devemail_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsinfradev_post['devemail']); ?>" size="32" maxlength="100" />
                <?php echo $tNGs->displayFieldHint("devemail");?> <?php echo $tNGs->displayFieldError("infradev_post", "devemail", $cnt1); ?></p></td>
          </tr>
          <tr>
            <td class="KT_th"><label for="devlocation_<?php echo $cnt1; ?>"><br />
            Project Location:</label></td>
            <td><p>
              <input type="text" name="devlocation_<?php echo $cnt1; ?>" id="devlocation_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsinfradev_post['devlocation']); ?>" size="32" />
                <?php echo $tNGs->displayFieldHint("devlocation");?> <?php echo $tNGs->displayFieldError("infradev_post", "devlocation", $cnt1); ?></p></td>
          </tr>
          <tr>
            <td class="KT_th"><label for="devstdate_<?php echo $cnt1; ?>"><br />
            Project Start Date:</label></td>
            <td><p>
              <input type="text" name="devstdate_<?php echo $cnt1; ?>" id="devstdate_<?php echo $cnt1; ?>" value="<?php echo KT_formatDate($row_rsinfradev_post['devstdate']); ?>" size="10" maxlength="22" />
                <?php echo $tNGs->displayFieldHint("devstdate");?> <?php echo $tNGs->displayFieldError("infradev_post", "devstdate", $cnt1); ?></p></td>
          </tr>
          <tr>
            <td class="KT_th"><label for="devduedate_<?php echo $cnt1; ?>"><br />
            Project Completion Date:</label></td>
            <td><p>
              <input type="text" name="devduedate_<?php echo $cnt1; ?>" id="devduedate_<?php echo $cnt1; ?>" value="<?php echo KT_formatDate($row_rsinfradev_post['devduedate']); ?>" size="10" maxlength="22" />
                <?php echo $tNGs->displayFieldHint("devduedate");?> <?php echo $tNGs->displayFieldError("infradev_post", "devduedate", $cnt1); ?></p></td>
          </tr>
        </table>
        <input type="hidden" name="kt_pk_infradev_post_<?php echo $cnt1; ?>" class="id_field" value="<?php echo KT_escapeAttribute($row_rsinfradev_post['kt_pk_infradev_post']); ?>" />
        <?php } while ($row_rsinfradev_post = mysql_fetch_assoc($rsinfradev_post)); ?>
      <div class="KT_bottombuttons" style="text-align:center;">
       <button style="margin-bottom: 10px; margin-top: 2px;"> <a href="../both/infradevpostdisp.php">Return To Development Postings Page</a> </button>
        <div>
          <?php 
      // Show IF Conditional region1
      if (@$_GET['devid'] == "") {
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