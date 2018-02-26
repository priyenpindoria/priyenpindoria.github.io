<?php require_once('Connections/connMISDS.php'); ?>
<?php
// Load the common classes
require_once('includes/common/KT_common.php');

// Load the tNG classes
require_once('includes/tng/tNG.inc.php');

// Load the KT_back class
require_once('includes/nxt/KT_back.php');

// Make a transaction dispatcher instance
$tNGs = new tNG_dispatcher("");

// Make unified connection variable
$conn_connMISDS = new KT_connection($connMISDS, $database_connMISDS);

//start Trigger_CheckPasswords trigger
//remove this line if you want to edit the code by hand
function Trigger_CheckPasswords(&$tNG) {
  $myThrowError = new tNG_ThrowError($tNG);
  $myThrowError->setErrorMsg("Could not create account.");
  $myThrowError->setField("password");
  $myThrowError->setFieldErrorMsg("The two passwords do not match.");
  return $myThrowError->Execute();
}
//end Trigger_CheckPasswords trigger

// Start trigger
$formValidation1 = new tNG_FormValidation();
$formValidation1->addField("kt_login_user", true, "text", "", "", "", "");
$formValidation1->addField("kt_login_password", true, "text", "", "", "", "");
$tNGs->prepareValidation($formValidation1);
// End trigger

// Start trigger
$formValidation = new tNG_FormValidation();
$formValidation->addField("firstname", true, "text", "", "", "", "");
$formValidation->addField("lastname", true, "text", "", "", "", "");
$formValidation->addField("username", true, "text", "", "", "", "");
$formValidation->addField("email", true, "text", "email", "", "", "");
$formValidation->addField("password", true, "text", "", "", "", "");
$formValidation->addField("dob", true, "date", "", "", "", "");
$tNGs->prepareValidation($formValidation);
// End trigger

//start Trigger_CheckOldPassword trigger
//remove this line if you want to edit the code by hand
function Trigger_CheckOldPassword(&$tNG) {
  return Trigger_UpdatePassword_CheckOldPassword($tNG);
}
//end Trigger_CheckOldPassword trigger

// Make an insert transaction instance
$ins_login_admin = new tNG_multipleInsert($conn_connMISDS);
$tNGs->addTransaction($ins_login_admin);
// Register triggers
$ins_login_admin->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Insert1");
$ins_login_admin->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$ins_login_admin->registerTrigger("END", "Trigger_Default_Redirect", 99, "includes/nxt/back.php");
$ins_login_admin->registerConditionalTrigger("{POST.password} != {POST.re_password}", "BEFORE", "Trigger_CheckPasswords", 50);
// Add columns
$ins_login_admin->setTable("login_admin");
$ins_login_admin->addColumn("firstname", "STRING_TYPE", "POST", "firstname");
$ins_login_admin->addColumn("lastname", "STRING_TYPE", "POST", "lastname");
$ins_login_admin->addColumn("username", "STRING_TYPE", "POST", "username");
$ins_login_admin->addColumn("email", "STRING_TYPE", "POST", "email");
$ins_login_admin->addColumn("password", "STRING_TYPE", "POST", "password");
$ins_login_admin->addColumn("dob", "DATE_TYPE", "POST", "dob");
$ins_login_admin->addColumn("levelid", "NUMERIC_TYPE", "POST", "levelid", "3");
$ins_login_admin->setPrimaryKey("logid", "NUMERIC_TYPE");

// Make an update transaction instance
$upd_login_admin = new tNG_multipleUpdate($conn_connMISDS);
$tNGs->addTransaction($upd_login_admin);
// Register triggers
$upd_login_admin->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Update1");
$upd_login_admin->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$upd_login_admin->registerTrigger("END", "Trigger_Default_Redirect", 99, "includes/nxt/back.php");
$upd_login_admin->registerConditionalTrigger("{POST.password} != {POST.re_password}", "BEFORE", "Trigger_CheckPasswords", 50);
$upd_login_admin->registerTrigger("BEFORE", "Trigger_CheckOldPassword", 60);
// Add columns
$upd_login_admin->setTable("login_admin");
$upd_login_admin->addColumn("firstname", "STRING_TYPE", "POST", "firstname");
$upd_login_admin->addColumn("lastname", "STRING_TYPE", "POST", "lastname");
$upd_login_admin->addColumn("username", "STRING_TYPE", "POST", "username");
$upd_login_admin->addColumn("email", "STRING_TYPE", "POST", "email");
$upd_login_admin->addColumn("password", "STRING_TYPE", "POST", "password");
$upd_login_admin->addColumn("dob", "DATE_TYPE", "POST", "dob");
$upd_login_admin->addColumn("levelid", "NUMERIC_TYPE", "POST", "levelid");
$upd_login_admin->setPrimaryKey("logid", "NUMERIC_TYPE", "GET", "logid");

// Make an instance of the transaction object
$del_login_admin = new tNG_multipleDelete($conn_connMISDS);
$tNGs->addTransaction($del_login_admin);
// Register triggers
$del_login_admin->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Delete1");
$del_login_admin->registerTrigger("END", "Trigger_Default_Redirect", 99, "includes/nxt/back.php");
// Add columns
$del_login_admin->setTable("login_admin");
$del_login_admin->setPrimaryKey("logid", "NUMERIC_TYPE", "GET", "logid");

// Make a login transaction instance
$loginTransaction = new tNG_login($conn_connMISDS);
$tNGs->addTransaction($loginTransaction);
// Register triggers
$loginTransaction->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "kt_login1");
$loginTransaction->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation1);
$loginTransaction->registerTrigger("END", "Trigger_Default_Redirect", 99, "{kt_login_redirect}");
// Add columns
$loginTransaction->addColumn("kt_login_user", "STRING_TYPE", "POST", "kt_login_user");
$loginTransaction->addColumn("kt_login_password", "STRING_TYPE", "POST", "kt_login_password");
$loginTransaction->addColumn("kt_login_rememberme", "CHECKBOX_1_0_TYPE", "POST", "kt_login_rememberme", "0");
// End of login transaction instance

// Execute all the registered transactions
$tNGs->executeTransactions();

// Get the transaction recordset
$rslogin_admin = $tNGs->getRecordset("login_admin");
$row_rslogin_admin = mysql_fetch_assoc($rslogin_admin);
$totalRows_rslogin_admin = mysql_num_rows($rslogin_admin);

// Get the transaction recordset
$rscustom = $tNGs->getRecordset("custom");
$row_rscustom = mysql_fetch_assoc($rscustom);
$totalRows_rscustom = mysql_num_rows($rscustom);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>MSIDS Login</title>
<link href="includes/skins/mxkollection3.css" rel="stylesheet" type="text/css" media="all" />
<script src="includes/common/js/base.js" type="text/javascript"></script>
<script src="includes/common/js/utility.js" type="text/javascript"></script>
<script src="includes/skins/style.js" type="text/javascript"></script>
<?php echo $tNGs->displayValidationRules();?>
<script src="includes/nxt/scripts/form.js" type="text/javascript"></script>
<script src="includes/nxt/scripts/form.js.php" type="text/javascript"></script>
<script type="text/javascript">
$NXT_FORM_SETTINGS = {
  duplicate_buttons: false,
  show_as_grid: false,
  merge_down_value: false
}
</script>
</head>

<style>
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

h1 {
	text-align: center;
	color: white;
}

body {background-color: #DDDDDD;}

.KT_tngform {
	margin: auto;
	border-radius: 50px;
	padding: 20px;
}

.KT_bottombuttons {
	text-align: center;

	}
.modal {
    display: block; 
    position: fixed; 
    z-index: 1; 
    left: 0;
    top: 0;
    width: 100%; 
    height: 100%; 
    overflow: auto; 
    background-color: rgb(0,0,0); 
    background-color: rgba(0,0,0,0.4); 
    padding-top: 60px;
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

h1 {
	text-align: center;
	color: white;
}

.KT_tngform {
	margin: auto;
	border-radius: 50px;
	padding: 20px;
}

.KT_buttons {
	text-align: center;

	}
.modal {
    display: none; 
    position: fixed; 
    z-index: 1; 
    left: 0;
    top: 0;
    width: 100%; 
    height: 100%; 
    overflow: auto; 
    background-color: rgb(0,0,0); 
    background-color: rgba(0,0,0,0.4); 
    padding-top: 60px;
}

.header {
	font-family: garamond header;
	font-size: 18px;
	color: #FFF;
	text-align: center;
	border-bottom-width: thin;
	border-bottom-style: solid;
	border-bottom-color: #7E80B4;
	margin-left: 10px;
	margin-right: 10px;
}

.KT_tngtable input, .KT_tngtable select {font-size: 12px;}

</style>

<body style="background-color:#C2E3EB;">
<p class="top_text" style="font-size: 30px; text-align: center; background-color:#56A99C;">MULTI-SECTOR INFORMATION DISTRIBUTION SYSTEM <br /> LOGIN PAGE</p>
</div>

<?php
	echo $tNGs->getLoginMsg();
?>
<form method="post" id="form2" style=" margin-left: 400px;" class="KT_tngformerror" action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>">
  <table cellpadding="2" cellspacing="0" class="KT_tngtable" style="border: 1px solid black; border-radius: 10px; padding: 10px;">
    <tr>
      <td width="131" class="KT_th"><label for="kt_login_user">Username:</label></td>
      <td width="275"><input type="text" name="kt_login_user" id="kt_login_user" value="<?php echo KT_escapeAttribute($row_rscustom['kt_login_user']); ?>" size="40" />
        <?php echo $tNGs->displayFieldHint("kt_login_user");?> <?php echo $tNGs->displayFieldError("custom", "kt_login_user"); ?></td>
    </tr>
    <tr>
      <td class="KT_th"><label for="kt_login_password">Password:</label></td>
      <td><input type="password" name="kt_login_password" id="kt_login_password" value="" size="40" />
        <?php echo $tNGs->displayFieldHint("kt_login_password");?> <?php echo $tNGs->displayFieldError("custom", "kt_login_password"); ?></td>
    </tr>
    <tr>
      <td class="KT_th"><label for="kt_login_rememberme">Remember me:</label></td>
      <td><input  <?php if (!(strcmp(KT_escapeAttribute($row_rscustom['kt_login_rememberme']),"1"))) {echo "checked";} ?> type="checkbox" name="kt_login_rememberme" id="kt_login_rememberme" value="1" />
        <?php echo $tNGs->displayFieldError("custom", "kt_login_rememberme"); ?></td>
    </tr>
    <tr class="KT_buttons">
      <td height="12" style="padding-right: 200px;" colspan="2" style="margin-right: 150px;"><input type="submit" name="kt_login1" id="kt_login1" value="Login" /></td>
 <tr>
     <td>Not a User? Sign Up  </td>
    <td><button style="margin-left: 20px;" onclick="document.getElementById('01').style.display='block'">Sign Up</button></td>
  </tr>
       
      
          
          
  </table>
</form>



<p>&nbsp;</p>
<div class="modal" id="01">
<?php
	echo $tNGs->getErrorMsg();
?>
<div class="KT_tng">
  <h1>
    <?php 
// Show IF Conditional region1 
if (@$_GET['logid'] == "") {
?>
      <?php echo NXT_getResource("Insert_FH"); ?>
      <?php 
// else Conditional region1
} else { ?>
      <?php echo NXT_getResource("Update_FH"); ?>
      <?php } 
// endif Conditional region1
?>
    A User</h1>
  <div class="KT_tngform">
    <form method="post" id="form1" action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>">
      <?php $cnt1 = 0; ?>
      <?php do { ?>
        <?php $cnt1++; ?>
        <?php 
// Show IF Conditional region1 
if (@$totalRows_rslogin_admin > 1) {
?>
          <h2><?php echo NXT_getResource("Record_FH"); ?> <?php echo $cnt1; ?></h2>
          <?php } 
// endif Conditional region1
?>
        <table cellpadding="2" cellspacing="0" class="KT_tngtable">
          <tr>
            <td width="147" class="KT_th"><label for="firstname_<?php echo $cnt1; ?>"><br />
              First Name:</label></td>
            <td width="360"><p>
              <input type="text" name="firstname_<?php echo $cnt1; ?>" id="firstname_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rslogin_admin['firstname']); ?>" size="32" maxlength="100" />
                <?php echo $tNGs->displayFieldHint("firstname");?> <?php echo $tNGs->displayFieldError("login_admin", "firstname", $cnt1); ?></p></td>
          </tr>
          <tr>
            <td class="KT_th"><label for="lastname_<?php echo $cnt1; ?>"><br />
              Surname:</label></td>
            <td><p>
              <input type="text" name="lastname_<?php echo $cnt1; ?>" id="lastname_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rslogin_admin['lastname']); ?>" size="32" maxlength="100" />
                <?php echo $tNGs->displayFieldHint("lastname");?> <?php echo $tNGs->displayFieldError("login_admin", "lastname", $cnt1); ?></p></td>
          </tr>
          <tr>
            <td class="KT_th"><label for="username_<?php echo $cnt1; ?>"><br />
              Username:</label></td>
            <td><p>
              <input type="text" name="username_<?php echo $cnt1; ?>" id="username_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rslogin_admin['username']); ?>" size="32" maxlength="100" />
                <?php echo $tNGs->displayFieldHint("username");?> <?php echo $tNGs->displayFieldError("login_admin", "username", $cnt1); ?></p></td>
          </tr>
          <tr>
            <td class="KT_th"><label for="email_<?php echo $cnt1; ?>"><br />
              Email:</label></td>
            <td><p>
              <input type="text" name="email_<?php echo $cnt1; ?>" id="email_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rslogin_admin['email']); ?>" size="32" maxlength="100" />
                <?php echo $tNGs->displayFieldHint("email");?> <?php echo $tNGs->displayFieldError("login_admin", "email", $cnt1); ?></p></td>
          </tr>
          <tr>
            <td class="KT_th"><label for="password_<?php echo $cnt1; ?>"><br />
              Password:</label></td>
            <td><p>
              <input type="password" name="password_<?php echo $cnt1; ?>" id="password_<?php echo $cnt1; ?>" value="" size="32" />
                <?php echo $tNGs->displayFieldHint("password");?> <?php echo $tNGs->displayFieldError("login_admin", "password", $cnt1); ?></p></td>
          </tr>
          <tr>
            <td class="KT_th"><label for="re_password_<?php echo $cnt1; ?>"><br />
              Re-type Password:</label></td>
            <td><p>
              <input type="password" name="re_password_<?php echo $cnt1; ?>" id="re_password_<?php echo $cnt1; ?>" value="" size="32" />
              </p></td>
          </tr>
          <tr>
            <td class="KT_th"><label for="dob_<?php echo $cnt1; ?>"><br />
              Date of Birth:</label></td>
            <td><p>
              <input type="text" name="dob_<?php echo $cnt1; ?>" id="dob_<?php echo $cnt1; ?>" value="<?php echo KT_formatDate($row_rslogin_admin['dob']); ?>" size="10" maxlength="22" />
                <?php echo $tNGs->displayFieldHint("dob");?> <?php echo $tNGs->displayFieldError("login_admin", "dob", $cnt1); ?></p></td>
          </tr>
        </table>
        <input type="hidden" name="kt_pk_login_admin_<?php echo $cnt1; ?>" class="id_field" value="<?php echo KT_escapeAttribute($row_rslogin_admin['kt_pk_login_admin']); ?>" />
        <input type="hidden" name="levelid_<?php echo $cnt1; ?>" id="levelid_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rslogin_admin['levelid']); ?>" />
        <?php } while ($row_rslogin_admin = mysql_fetch_assoc($rslogin_admin)); ?>
      <div class="KT_bottombuttons">
        <div>
          <?php 
      // Show IF Conditional region1
      if (@$_GET['logid'] == "") {
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
          <input type="button" name="KT_Cancel1" value="<?php echo NXT_getResource("Cancel_FB"); ?>" onclick="return UNI_navigateCancel(event, 'includes/nxt/back.php')" />
        </div>
      </div>
    </form>
  </div>
  <br class="clearfixplain" />
</div>
<p>&nbsp;</p>
</div>
</body>
</html>