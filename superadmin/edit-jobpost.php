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
$formValidation->addField("jobname", true, "text", "", "", "", "");
$formValidation->addField("company", true, "text", "", "", "", "");
$formValidation->addField("number", true, "text", "", "", "", "");
$formValidation->addField("email", true, "text", "email", "", "", "");
$formValidation->addField("location", true, "text", "", "", "", "");
$formValidation->addField("timings", true, "text", "", "", "", "");
$formValidation->addField("salary", true, "text", "", "", "", "");
$formValidation->addField("deadline", false, "date", "date", "", "", "");
$tNGs->prepareValidation($formValidation);
// End trigger

// Make an insert transaction instance
$ins_jobs_post = new tNG_multipleInsert($conn_connMISDS);
$tNGs->addTransaction($ins_jobs_post);
// Register triggers
$ins_jobs_post->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Insert1");
$ins_jobs_post->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$ins_jobs_post->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
// Add columns
$ins_jobs_post->setTable("jobs_post");
$ins_jobs_post->addColumn("jobname", "STRING_TYPE", "POST", "jobname");
$ins_jobs_post->addColumn("company", "STRING_TYPE", "POST", "company");
$ins_jobs_post->addColumn("number", "STRING_TYPE", "POST", "number");
$ins_jobs_post->addColumn("email", "STRING_TYPE", "POST", "email");
$ins_jobs_post->addColumn("location", "STRING_TYPE", "POST", "location");
$ins_jobs_post->addColumn("timings", "STRING_TYPE", "POST", "timings");
$ins_jobs_post->addColumn("salary", "STRING_TYPE", "POST", "salary");
$ins_jobs_post->addColumn("deadline", "DATE_TYPE", "POST", "deadline");
$ins_jobs_post->setPrimaryKey("jobid", "NUMERIC_TYPE");

// Make an update transaction instance
$upd_jobs_post = new tNG_multipleUpdate($conn_connMISDS);
$tNGs->addTransaction($upd_jobs_post);
// Register triggers
$upd_jobs_post->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Update1");
$upd_jobs_post->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$upd_jobs_post->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
// Add columns
$upd_jobs_post->setTable("jobs_post");
$upd_jobs_post->addColumn("jobname", "STRING_TYPE", "POST", "jobname");
$upd_jobs_post->addColumn("company", "STRING_TYPE", "POST", "company");
$upd_jobs_post->addColumn("number", "STRING_TYPE", "POST", "number");
$upd_jobs_post->addColumn("email", "STRING_TYPE", "POST", "email");
$upd_jobs_post->addColumn("location", "STRING_TYPE", "POST", "location");
$upd_jobs_post->addColumn("timings", "STRING_TYPE", "POST", "timings");
$upd_jobs_post->addColumn("salary", "STRING_TYPE", "POST", "salary");
$upd_jobs_post->addColumn("deadline", "DATE_TYPE", "POST", "deadline");
$upd_jobs_post->setPrimaryKey("jobid", "NUMERIC_TYPE", "GET", "jobid");

// Make an instance of the transaction object
$del_jobs_post = new tNG_multipleDelete($conn_connMISDS);
$tNGs->addTransaction($del_jobs_post);
// Register triggers
$del_jobs_post->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Delete1");
$del_jobs_post->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
// Add columns
$del_jobs_post->setTable("jobs_post");
$del_jobs_post->setPrimaryKey("jobid", "NUMERIC_TYPE", "GET", "jobid");

// Execute all the registered transactions
$tNGs->executeTransactions();

// Get the transaction recordset
$rsjobs_post = $tNGs->getRecordset("jobs_post");
$row_rsjobs_post = mysql_fetch_assoc($rsjobs_post);
$totalRows_rsjobs_post = mysql_num_rows($rsjobs_post);
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
if (@$_GET['jobid'] == "") {
?>
      <?php echo NXT_getResource("Insert_FH"); ?>
      <?php 
// else Conditional region1
} else { ?>
      <?php echo NXT_getResource("Update_FH"); ?>
      <?php } 
// endif Conditional region1
?>
    A Job</h1>
  <div class="KT_tngform">
    <form method="post" id="form1" action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>">
      <?php $cnt1 = 0; ?>
      <?php do { ?>
        <?php $cnt1++; ?>
        <?php 
// Show IF Conditional region1 
if (@$totalRows_rsjobs_post > 1) {
?>
          <h2><?php echo NXT_getResource("Record_FH"); ?> <?php echo $cnt1; ?></h2>
          <?php } 
// endif Conditional region1
?>
        <table cellpadding="2" cellspacing="0" class="KT_tngtable">
          <tr>
            <td width="142" class="KT_th"><label for="jobname_<?php echo $cnt1; ?>"><br />
              Job Name:</label></td>
            <td width="364"><p>
              <input type="text" name="jobname_<?php echo $cnt1; ?>" id="jobname_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsjobs_post['jobname']); ?>" size="32" maxlength="100" />
                <?php echo $tNGs->displayFieldHint("jobname");?> <?php echo $tNGs->displayFieldError("jobs_post", "jobname", $cnt1); ?></p></td>
          </tr>
          <tr>
            <td class="KT_th"><label for="company_<?php echo $cnt1; ?>"><br />
              Company:</label></td>
            <td><p>
              <input type="text" name="company_<?php echo $cnt1; ?>" id="company_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsjobs_post['company']); ?>" size="32" />
                <?php echo $tNGs->displayFieldHint("company");?> <?php echo $tNGs->displayFieldError("jobs_post", "company", $cnt1); ?></p></td>
          </tr>
          <tr>
            <td class="KT_th"><label for="number_<?php echo $cnt1; ?>"><br />
              Number:</label></td>
            <td><p>
              <input type="text" name="number_<?php echo $cnt1; ?>" id="number_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsjobs_post['number']); ?>" size="32" maxlength="100" />
                <?php echo $tNGs->displayFieldHint("number");?> <?php echo $tNGs->displayFieldError("jobs_post", "number", $cnt1); ?></p></td>
          </tr>
          <tr>
            <td class="KT_th"><label for="email_<?php echo $cnt1; ?>"><br />
              Email:</label></td>
            <td><p>
              <input type="text" name="email_<?php echo $cnt1; ?>" id="email_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsjobs_post['email']); ?>" size="32" maxlength="100" />
                <?php echo $tNGs->displayFieldHint("email");?> <?php echo $tNGs->displayFieldError("jobs_post", "email", $cnt1); ?></p></td>
          </tr>
          <tr>
            <td class="KT_th"><label for="location_<?php echo $cnt1; ?>"><br />
              Location:</label></td>
            <td><p>
              <input type="text" name="location_<?php echo $cnt1; ?>" id="location_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsjobs_post['location']); ?>" size="32" maxlength="100" />
                <?php echo $tNGs->displayFieldHint("location");?> <?php echo $tNGs->displayFieldError("jobs_post", "location", $cnt1); ?></p></td>
          </tr>
          <tr>
            <td class="KT_th"><label for="timings_<?php echo $cnt1; ?>"><br />
              Timings:</label></td>
            <td><p>
              <input type="text" name="timings_<?php echo $cnt1; ?>" id="timings_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsjobs_post['timings']); ?>" size="32" />
                <?php echo $tNGs->displayFieldHint("timings");?> <?php echo $tNGs->displayFieldError("jobs_post", "timings", $cnt1); ?></p></td>
          </tr>
          <tr>
            <td class="KT_th"><label for="salary_<?php echo $cnt1; ?>"><br />
              Salary:</label></td>
            <td><p>
              <input type="text" name="salary_<?php echo $cnt1; ?>" id="salary_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsjobs_post['salary']); ?>" size="32" />
                <?php echo $tNGs->displayFieldHint("salary");?> <?php echo $tNGs->displayFieldError("jobs_post", "salary", $cnt1); ?></p></td>
          </tr>
          <tr>
            <td class="KT_th"><label for="deadline_<?php echo $cnt1; ?>"><br />
              Deadline:</label></td>
            <td><p>
              <input type="text" name="deadline_<?php echo $cnt1; ?>" id="deadline_<?php echo $cnt1; ?>" value="<?php echo KT_formatDate($row_rsjobs_post['deadline']); ?>" size="10" maxlength="22" />
                <?php echo $tNGs->displayFieldHint("deadline");?> <?php echo $tNGs->displayFieldError("jobs_post", "deadline", $cnt1); ?></p></td>
          </tr>
        </table>
        <input type="hidden" name="kt_pk_jobs_post_<?php echo $cnt1; ?>" class="id_field" value="<?php echo KT_escapeAttribute($row_rsjobs_post['kt_pk_jobs_post']); ?>" />
        <?php } while ($row_rsjobs_post = mysql_fetch_assoc($rsjobs_post)); ?>
      <div class="KT_bottombuttons">
      <button style="margin-bottom: 10px; margin-top: 2px;"> <a href="jobpostmodal.php">Return To Job Postings Page</a> </button>

        <div>
          <?php 
      // Show IF Conditional region1
      if (@$_GET['jobid'] == "") {
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