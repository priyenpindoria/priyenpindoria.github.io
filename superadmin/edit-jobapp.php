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
$formValidation->addField("applicantname", true, "text", "", "", "", "");
$formValidation->addField("jobid", true, "text", "", "", "", "");
$formValidation->addField("jobcompany", true, "text", "", "", "", "");
$formValidation->addField("idnumber", true, "numeric", "", "", "", "");
$formValidation->addField("Nationality", true, "text", "", "", "", "");
$formValidation->addField("dob", true, "date", "date", "", "", "");
$formValidation->addField("number", true, "text", "", "", "", "");
$formValidation->addField("email", true, "text", "email", "", "", "");
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
$query_post = "SELECT * FROM jobs_post";
$post = mysql_query($query_post, $connMISDS) or die(mysql_error());
$row_post = mysql_fetch_assoc($post);
$totalRows_post = mysql_num_rows($post);

mysql_select_db($database_connMISDS, $connMISDS);
$query_nationality = "SELECT * FROM country";
$nationality = mysql_query($query_nationality, $connMISDS) or die(mysql_error());
$row_nationality = mysql_fetch_assoc($nationality);
$totalRows_nationality = mysql_num_rows($nationality);

// Make an insert transaction instance
$ins_jobs_app = new tNG_multipleInsert($conn_connMISDS);
$tNGs->addTransaction($ins_jobs_app);
// Register triggers
$ins_jobs_app->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Insert1");
$ins_jobs_app->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$ins_jobs_app->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
// Add columns
$ins_jobs_app->setTable("jobs_app");
$ins_jobs_app->addColumn("applicantname", "STRING_TYPE", "POST", "applicantname");
$ins_jobs_app->addColumn("jobid", "STRING_TYPE", "POST", "jobid");
$ins_jobs_app->addColumn("jobcompany", "STRING_TYPE", "POST", "jobcompany");
$ins_jobs_app->addColumn("idnumber", "NUMERIC_TYPE", "POST", "idnumber");
$ins_jobs_app->addColumn("Nationality", "STRING_TYPE", "POST", "Nationality");
$ins_jobs_app->addColumn("prevexperiences", "STRING_TYPE", "POST", "prevexperiences");
$ins_jobs_app->addColumn("dob", "DATE_TYPE", "POST", "dob");
$ins_jobs_app->addColumn("number", "STRING_TYPE", "POST", "number");
$ins_jobs_app->addColumn("email", "STRING_TYPE", "POST", "email");
$ins_jobs_app->setPrimaryKey("applicantid", "NUMERIC_TYPE");

// Make an update transaction instance
$upd_jobs_app = new tNG_multipleUpdate($conn_connMISDS);
$tNGs->addTransaction($upd_jobs_app);
// Register triggers
$upd_jobs_app->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Update1");
$upd_jobs_app->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$upd_jobs_app->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
// Add columns
$upd_jobs_app->setTable("jobs_app");
$upd_jobs_app->addColumn("applicantname", "STRING_TYPE", "POST", "applicantname");
$upd_jobs_app->addColumn("jobid", "STRING_TYPE", "POST", "jobid");
$upd_jobs_app->addColumn("jobcompany", "STRING_TYPE", "POST", "jobcompany");
$upd_jobs_app->addColumn("idnumber", "NUMERIC_TYPE", "POST", "idnumber");
$upd_jobs_app->addColumn("Nationality", "STRING_TYPE", "POST", "Nationality");
$upd_jobs_app->addColumn("prevexperiences", "STRING_TYPE", "POST", "prevexperiences");
$upd_jobs_app->addColumn("dob", "DATE_TYPE", "POST", "dob");
$upd_jobs_app->addColumn("number", "STRING_TYPE", "POST", "number");
$upd_jobs_app->addColumn("email", "STRING_TYPE", "POST", "email");
$upd_jobs_app->setPrimaryKey("applicantid", "NUMERIC_TYPE", "GET", "applicantid");

// Make an instance of the transaction object
$del_jobs_app = new tNG_multipleDelete($conn_connMISDS);
$tNGs->addTransaction($del_jobs_app);
// Register triggers
$del_jobs_app->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Delete1");
$del_jobs_app->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
// Add columns
$del_jobs_app->setTable("jobs_app");
$del_jobs_app->setPrimaryKey("applicantid", "NUMERIC_TYPE", "GET", "applicantid");

// Execute all the registered transactions
$tNGs->executeTransactions();

// Get the transaction recordset
$rsjobs_app = $tNGs->getRecordset("jobs_app");
$row_rsjobs_app = mysql_fetch_assoc($rsjobs_app);
$totalRows_rsjobs_app = mysql_num_rows($rsjobs_app);
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
if (@$_GET['applicantid'] == "") {
?>
      <?php echo NXT_getResource("Insert_FH"); ?>
      <?php 
// else Conditional region1
} else { ?>
      <?php echo NXT_getResource("Update_FH"); ?>
      <?php } 
// endif Conditional region1
?>
    A Job Application</h1>
  <div class="KT_tngform">
    <form method="post" id="form1" action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>">
      <?php $cnt1 = 0; ?>
      <?php do { ?>
        <?php $cnt1++; ?>
        <?php 
// Show IF Conditional region1 
if (@$totalRows_rsjobs_app > 1) {
?>
          <h2><?php echo NXT_getResource("Record_FH"); ?> <?php echo $cnt1; ?></h2>
          <?php } 
// endif Conditional region1
?>
        <table cellpadding="2" cellspacing="0" class="KT_tngtable">
          <tr>
            <td class="KT_th"><label for="applicantname_<?php echo $cnt1; ?>"><br />
              Name:</label></td>
            <td><p>
              <input type="text" name="applicantname_<?php echo $cnt1; ?>" id="applicantname_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsjobs_app['applicantname']); ?>" size="32" />
                <?php echo $tNGs->displayFieldHint("applicantname");?> <?php echo $tNGs->displayFieldError("jobs_app", "applicantname", $cnt1); ?></p></td>
          </tr>
          <tr>
            <td class="KT_th"><label for="jobid_<?php echo $cnt1; ?>"><br />
              Job:</label></td>
            <td><p>
              <select name="jobid_<?php echo $cnt1; ?>" id="jobid_<?php echo $cnt1; ?>">
                <option value=""><?php echo NXT_getResource("Select one..."); ?></option>
                <?php 
do {  
?>
                <option value="<?php echo $row_post['jobname']?>"<?php if (!(strcmp($row_post['jobname'], $row_rsjobs_app['jobid']))) {echo "SELECTED";} ?>><?php echo $row_post['jobname']?></option>
                <?php
} while ($row_post = mysql_fetch_assoc($post));
  $rows = mysql_num_rows($post);
  if($rows > 0) {
      mysql_data_seek($post, 0);
	  $row_post = mysql_fetch_assoc($post);
  }
?>
              </select>
                <?php echo $tNGs->displayFieldError("jobs_app", "jobid", $cnt1); ?></p></td>
          </tr>
          <tr>
            <td class="KT_th"><label for="jobcompany_<?php echo $cnt1; ?>"><br />
              Job Company:</label></td>
            <td><p>
              <select name="jobcompany_<?php echo $cnt1; ?>" id="jobcompany_<?php echo $cnt1; ?>">
                <option value=""><?php echo NXT_getResource("Select one..."); ?></option>
                <?php 
do {  
?>
                <option value="<?php echo $row_post['company']?>"<?php if (!(strcmp($row_post['company'], $row_rsjobs_app['jobcompany']))) {echo "SELECTED";} ?>><?php echo $row_post['company']?></option>
                <?php
} while ($row_post = mysql_fetch_assoc($post));
  $rows = mysql_num_rows($post);
  if($rows > 0) {
      mysql_data_seek($post, 0);
	  $row_post = mysql_fetch_assoc($post);
  }
?>
              </select>
                <?php echo $tNGs->displayFieldError("jobs_app", "jobcompany", $cnt1); ?></p></td>
          </tr>
          <tr>
            <td class="KT_th"><label for="idnumber_<?php echo $cnt1; ?>"><br />
              ID Number:</label></td>
            <td><p>
              <input type="text" name="idnumber_<?php echo $cnt1; ?>" id="idnumber_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsjobs_app['idnumber']); ?>" size="7" />
                <?php echo $tNGs->displayFieldHint("idnumber");?> <?php echo $tNGs->displayFieldError("jobs_app", "idnumber", $cnt1); ?></p></td>
          </tr>
          <tr>
            <td class="KT_th"><label for="Nationality_<?php echo $cnt1; ?>"><br />
              Nationality:</label></td>
            <td><p>
              <select name="Nationality_<?php echo $cnt1; ?>" id="Nationality_<?php echo $cnt1; ?>">
                <option value=""><?php echo NXT_getResource("Select one..."); ?></option>
                <?php 
do {  
?>
                <option value="<?php echo $row_nationality['nationality']?>"<?php if (!(strcmp($row_nationality['nationality'], $row_rsjobs_app['Nationality']))) {echo "SELECTED";} ?>><?php echo $row_nationality['nationality']?></option>
                <?php
} while ($row_nationality = mysql_fetch_assoc($nationality));
  $rows = mysql_num_rows($nationality);
  if($rows > 0) {
      mysql_data_seek($nationality, 0);
	  $row_nationality = mysql_fetch_assoc($nationality);
  }
?>
              </select>
                <?php echo $tNGs->displayFieldError("jobs_app", "Nationality", $cnt1); ?></p></td>
          </tr>
          <tr>
            <td class="KT_th"><label for="prevexperiences_<?php echo $cnt1; ?>"><br />
              Experiences and Qualifications:</label></td>
            <td><p>
              <textarea name="prevexperiences_<?php echo $cnt1; ?>" id="prevexperiences_<?php echo $cnt1; ?>" cols="50" rows="5"><?php echo KT_escapeAttribute($row_rsjobs_app['prevexperiences']); ?></textarea>
                <?php echo $tNGs->displayFieldHint("prevexperiences");?> <?php echo $tNGs->displayFieldError("jobs_app", "prevexperiences", $cnt1); ?></p></td>
          </tr>
          <tr>
            <td class="KT_th"><label for="dob_<?php echo $cnt1; ?>"><br />
              Date of Birth:</label></td>
            <td><p>
              <input type="text" name="dob_<?php echo $cnt1; ?>" id="dob_<?php echo $cnt1; ?>" value="<?php echo KT_formatDate($row_rsjobs_app['dob']); ?>" size="10" maxlength="22" />
                <?php echo $tNGs->displayFieldHint("dob");?> <?php echo $tNGs->displayFieldError("jobs_app", "dob", $cnt1); ?></p></td>
          </tr>
          <tr>
            <td class="KT_th"><label for="number_<?php echo $cnt1; ?>"><br />
              Phone Number:</label></td>
            <td><p>
              <input type="text" name="number_<?php echo $cnt1; ?>" id="number_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsjobs_app['number']); ?>" size="32" maxlength="100" />
                <?php echo $tNGs->displayFieldHint("number");?> <?php echo $tNGs->displayFieldError("jobs_app", "number", $cnt1); ?></p></td>
          </tr>
          <tr>
            <td class="KT_th"><label for="email_<?php echo $cnt1; ?>"><br />
              Email:</label></td>
            <td><p>
              <input type="text" name="email_<?php echo $cnt1; ?>" id="email_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsjobs_app['email']); ?>" size="32" maxlength="100" />
                <?php echo $tNGs->displayFieldHint("email");?> <?php echo $tNGs->displayFieldError("jobs_app", "email", $cnt1); ?></p></td>
          </tr>
        </table>
        <input type="hidden" name="kt_pk_jobs_app_<?php echo $cnt1; ?>" class="id_field" value="<?php echo KT_escapeAttribute($row_rsjobs_app['kt_pk_jobs_app']); ?>" />
        <?php } while ($row_rsjobs_app = mysql_fetch_assoc($rsjobs_app)); ?>
      <div class="KT_bottombuttons">
      <button style="margin-bottom: 10px; margin-top: 2px;"> <a href="jobpostmodal.php">Return To Job Postings Page</a> </button>

        <div>
          <?php 
      // Show IF Conditional region1
      if (@$_GET['applicantid'] == "") {
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
mysql_free_result($post);

mysql_free_result($nationality);
?>
