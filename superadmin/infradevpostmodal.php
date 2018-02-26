<?php


mysql_connect('localhost','root','');

mysql_select_db('msids');

$sql='SELECT * FROM infradev_post';

$records=mysql_query($sql);

?>

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
$ins_infradev_post->registerTrigger("END", "Trigger_Default_Redirect", 99, "infradevpostmodal.php");
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
$upd_infradev_post->registerTrigger("END", "Trigger_Default_Redirect", 99, "infradevpostmodal.php");
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
$del_infradev_post->registerTrigger("END", "Trigger_Default_Redirect", 99, "infradevpostmodal.php");
// Add columns
$del_infradev_post->setTable("infradev_post");
$del_infradev_post->setPrimaryKey("devid", "NUMERIC_TYPE", "GET", "devid");

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

// Get the transaction recordset
$rsinfradev_post = $tNGs->getRecordset("infradev_post");
$row_rsinfradev_post = mysql_fetch_assoc($rsinfradev_post);
$totalRows_rsinfradev_post = mysql_num_rows($rsinfradev_post);

// Get the transaction recordset
$rscustom = $tNGs->getRecordset("custom");
$row_rscustom = mysql_fetch_assoc($rscustom);
$totalRows_rscustom = mysql_num_rows($rscustom);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>MSIDS Development Postings</title>
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
<!-- <style>
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

.header {
	font-family: Georgia, "Times New Roman", Times, serif;
	font-size: 18px;
	color: gold;
	text-align: center;
	border-bottom-width: thin;
	border-bottom-style: solid;
	border-bottom-color: #00F;
	margin-left: 0px;
	margin-right: 0px;	
	background-color: #C60000;
	font-size: 25px;

}
.post {
	border: 1px solid #CCC;
	width: 150px;
	
	text-align: center;
	font-family: Arial, Helvetica, sans-serif;
	color: #000;
	margin-top: 10px;
	margin-bottom: 10px;
}

.intro_text {
	font-size: 12px;
	color:#000;
	padding-bottom: 1px;
	text-align: center;
	margin-bottom: 12px;
	text-weight: boid;
	font-family: arial, helvetica, sans-serif;
	}	
	
table {
	border: solid 0px #090;
	margin: auto;
	}
	
tr:nth-child(even) {
	
	background-color: lightgrey;

	}		
	
th {
	padding: 10px;
	background-color: #7E80B4;
	font-family: Georgia, "Times New Roman", Times, serif;
	font-weight: bold;
	font-size: 12px;
	text-align: left;
	line-height: 24px;
	}	

td {
	border-bottom: 2px solid #666;
	padding: 10px;
	font-size: 12px;
	font-family: Arial, Helvetica, sans-serif;
	line-height: 24px;
	
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
.buttons {margin-left: 400px; font-size: 14px;}

</style> -->
<link rel="stylesheet" type="text/css" href="css_test_modals.css" />
<body>
<div class="nav_bar">
<ul>
    	<li><a href="index.php">Home</a></li>
        <li><a href="../superadmin/edumodal.php">Education</a></li>
        <li><a href="../superadmin/envmodal.php">Environment</a></li>
        <li><a href="../superadmin/healthmodal.php">Health</a></li>
        <li><a>Jobs</a>
        	<ul>
            	<li><a href="../superadmin/jobpostmodal.php">Job Postings</a></li>
                <li><a href="../superadmin/jobappmodal.php">Job Applicants</a></li>
            </ul>
        </li>
      <li><a>Infrastructure</a>
        	<ul>
            	<li><a href="../superadmin/infradevpostmodal.php">Development Postings</a></li>
            	<li><a href="../superadmin/infradevappmodal.php">Development Applicants</a></li>
            </ul>
    </li>    
               	<li><a href="<?php echo $logoutTransaction->getLogoutLink(); ?>">Logout</a></li>
            
  </ul>
<br />
<br />
</div>
<p class="header"><strong>MSIDS DEVELOPMENT POSTINGS</strong></p>

<h1 class="intro_text">Welcome to MSIDS development postings section. Here you will be able to preview all the infrastructure projects in the Nairobi County.</h1>
<input type="text" id="myInput" onkeyup="myFunction()" placeholder="Search for A Post..." title="Type in a name">
<script>
function sortTable(n) {
  var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
  table = document.getElementById("myTable");
  switching = true;
  //Set the sorting direction to ascending:
  dir = "asc"; 
  /*Make a loop that will continue until
  no switching has been done:*/
  while (switching) {
    //start by saying: no switching is done:
    switching = false;
    rows = table.getElementsByTagName("TR");
    /*Loop through all table rows (except the
    first, which contains table headers):*/
    for (i = 1; i < (rows.length - 1); i++) {
      //start by saying there should be no switching:
      shouldSwitch = false;
      /*Get the two elements you want to compare,
      one from current row and one from the next:*/
      x = rows[i].getElementsByTagName("TD")[n];
      y = rows[i + 1].getElementsByTagName("TD")[n];
      /*check if the two rows should switch place,
      based on the direction, asc or desc:*/
      if (dir == "asc") {
        if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
          //if so, mark as a switch and break the loop:
          shouldSwitch= true;
          break;
        }
      } else if (dir == "desc") {
        if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
          //if so, mark as a switch and break the loop:
          shouldSwitch= true;
          break;
        }
      }
    }
    if (shouldSwitch) {
      /*If a switch has been marked, make the switch
      and mark that a switch has been done:*/
      rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
      switching = true;
      //Each time a switch is done, increase this count by 1:
      switchcount ++;      
    } else {
      /*If no switching has been done AND the direction is "asc",
      set the direction to "desc" and run the while loop again.*/
      if (switchcount == 0 && dir == "asc") {
        dir = "desc";
        switching = true;
      }
    }
  }
}
</script>

<script>
function myFunction() {
  var input, filter, table, tr, td, i;
  input = document.getElementById("myInput");
  filter = input.value.toUpperCase();
  table = document.getElementById("myTable");
  tr = table.getElementsByTagName("tr");
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[0];
    if (td) {
      if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }       
  }
}
</script>

<table width="1089" border="0" align="center" cellpadding="1" cellspacing="1" bordercolor="#FF3366" class="hui" id="myTable">
  <tr class="row">
    <th width="139" onclick="sortTable(0)">Development Name</th>
    <th width="322" height="32" onclick="sortTable(1)">Description</th>
    <th width="115" onclick="sortTable(2)">Client</th>
    <th width="84" onclick="sortTable(3)">Number</th>
    <th width="91" onclick="sortTable(4)">Email</th>
    <th width="101" onclick="sortTable(5)">Location</th>
    <th width="80" onclick="sortTable(6)">Start Date</th>
    <th width="95" onclick="sortTable(7)">End Date</th>
  </tr>
  <?php
while($infradevpost=mysql_fetch_assoc($records)) {

 echo "<tr>";
	echo "<td>".$infradevpost['devname']."</td>";
	echo "<td>".$infradevpost['devdesc']."</td>";
	echo "<td>".$infradevpost['devcompany']."</td>";
	echo "<td>".$infradevpost['devnumber']."</td>";
	echo "<td>".$infradevpost['devemail']."</td>";
	echo "<td>".$infradevpost['devlocation']."</td>";
	echo "<td>".$infradevpost['devstdate']."</td>";
	echo "<td>".$infradevpost['devduedate']."</td>";
	
	echo "</tr>";

}

?>
</table>
<div class="buttons">
<button class="post" style="margin-right:30px; color: black;" onclick="document.getElementById('01').style.display='block'">Add A New Project</button>
<button class="post" style="margin-right:30px; color: black;"><a style="font-size: 14px; color: black;" href="../superadmin/edit-infradevapp.php">Apply for a project</a></button>
<button class="post" style="margin-right:30px;"><a style="font-size: 14px; color: black;;" href="edit-companies.php">Add Your Company</a></button>
<button class="post"><a href="flag_posts.php" style="color: black;">Report A Post</a></button>
</div>

<div id="01" class="modal" style="display: none;">
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
</div>
</body>
</html>