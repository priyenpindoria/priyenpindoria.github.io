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
$formValidation->addField("subsector", true, "text", "", "", "", "");
$formValidation->addField("title", true, "text", "", "", "", "");
$formValidation->addField("description", true, "text", "", "", "", "");
$formValidation->addField("location", true, "text", "", "", "", "");
$formValidation->addField("date", true, "date", "date", "", "", "");
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
$query_rsDropdown = "SELECT * FROM health_subsector";
$rsDropdown = mysql_query($query_rsDropdown, $connMISDS) or die(mysql_error());
$row_rsDropdown = mysql_fetch_assoc($rsDropdown);
$totalRows_rsDropdown = mysql_num_rows($rsDropdown);

// Make an insert transaction instance
$ins_health = new tNG_multipleInsert($conn_connMISDS);
$tNGs->addTransaction($ins_health);
// Register triggers
$ins_health->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Insert1");
$ins_health->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$ins_health->registerTrigger("END", "Trigger_Default_Redirect", 99, "healthmodal.php");
// Add columns
$ins_health->setTable("health");
$ins_health->addColumn("subsector", "STRING_TYPE", "POST", "subsector");
$ins_health->addColumn("title", "STRING_TYPE", "POST", "title");
$ins_health->addColumn("description", "STRING_TYPE", "POST", "description");
$ins_health->addColumn("location", "STRING_TYPE", "POST", "location");
$ins_health->addColumn("date", "DATE_TYPE", "POST", "date");
$ins_health->setPrimaryKey("newsid", "NUMERIC_TYPE");

// Make an update transaction instance
$upd_health = new tNG_multipleUpdate($conn_connMISDS);
$tNGs->addTransaction($upd_health);
// Register triggers
$upd_health->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Update1");
$upd_health->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$upd_health->registerTrigger("END", "Trigger_Default_Redirect", 99, "healthmodal.php");
// Add columns
$upd_health->setTable("health");
$upd_health->addColumn("subsector", "STRING_TYPE", "POST", "subsector");
$upd_health->addColumn("title", "STRING_TYPE", "POST", "title");
$upd_health->addColumn("description", "STRING_TYPE", "POST", "description");
$upd_health->addColumn("location", "STRING_TYPE", "POST", "location");
$upd_health->addColumn("date", "DATE_TYPE", "POST", "date");
$upd_health->setPrimaryKey("newsid", "NUMERIC_TYPE", "GET", "newsid");

// Make an instance of the transaction object
$del_health = new tNG_multipleDelete($conn_connMISDS);
$tNGs->addTransaction($del_health);
// Register triggers
$del_health->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Delete1");
$del_health->registerTrigger("END", "Trigger_Default_Redirect", 99, "healthmodal.php");
// Add columns
$del_health->setTable("health");
$del_health->setPrimaryKey("newsid", "NUMERIC_TYPE", "GET", "newsid");

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
$rshealth = $tNGs->getRecordset("health");
$row_rshealth = mysql_fetch_assoc($rshealth);
$totalRows_rshealth = mysql_num_rows($rshealth);

// Get the transaction recordset
$rscustom = $tNGs->getRecordset("custom");
$row_rscustom = mysql_fetch_assoc($rscustom);
$totalRows_rscustom = mysql_num_rows($rscustom);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>MSIDS Health</title>
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
<!--
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



<?php


mysql_connect('localhost','root','');

mysql_select_db('msids');

$sql='SELECT * FROM health';

$records=mysql_query($sql);

?>

<p class="header"><strong>MSIDS HEALTH</strong></p>

<h1 class="intro_text">Welcome to MSIDS health section. Here you will be able to view all the health information of the Nairobi County.</h1>

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
<table width="1116" align="center" cellpadding="5" cellspacing="0"  class="hui" border="0" id="myTable">
  <tr>
    <th width="199" height="46" class="height" onclick="sortTable(0)">Title</th>
    <th width="420" class="height" onclick="sortTable(1)">Description</th>
    <th width="206" onclick="sortTable(2)">Location</th>
    <th width="136" class="height" onclick="sortTable(3)">Date</th>
	<th width="85" onclick="sortTable(4)">Sub Sector</th>
  </tr>
  <?php
while($health=mysql_fetch_assoc($records)) {

 echo "<tr>";
	
	echo "<td>".$health['title']."</td>";
	echo "<td>".$health['description']."</td>";
	echo "<td>".$health['location']."</td>";
	echo "<td>".$health['date']."</td>";
	echo "<td>".$health['subsector']."</td>";
	echo "</tr>";

}

?>
</table>
<button class="post" onclick="document.getElementById('01').style.display='block'">Add A New Health Post</button>
<button class="post"><a style="color: black;" href="flag_posts.php">Report A Post</a></button>
<div class="modal" id="01" style="display: none;">
<?php
	echo $tNGs->getErrorMsg();
?>
<div class="KT_tng">
  <h1>
    <?php 
// Show IF Conditional region1 
if (@$_GET['newsid'] == "") {
?>
      <?php echo NXT_getResource("Insert_FH"); ?>
      <?php 
// else Conditional region1
} else { ?>
      <?php echo NXT_getResource("Update_FH"); ?>
      <?php } 
// endif Conditional region1
?>
    Health </h1>
  <div class="KT_tngform">
    <form method="post" id="form1" action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>">
      <?php $cnt1 = 0; ?>
      <?php do { ?>
        <?php $cnt1++; ?>
        <?php 
// Show IF Conditional region1 
if (@$totalRows_rshealth > 1) {
?>
          <h2><?php echo NXT_getResource("Record_FH"); ?> <?php echo $cnt1; ?></h2>
          <?php } 
// endif Conditional region1
?>
        <table cellpadding="2" cellspacing="0" class="KT_tngtable">
          <tr>
            <td width="146" class="KT_th"><label for="subsector_<?php echo $cnt1; ?>"><br />
            Subsector:</label></td>
            <td width="338"><p>
              <select name="subsector_<?php echo $cnt1; ?>" id="subsector_<?php echo $cnt1; ?>">
                <option value=""><?php echo NXT_getResource("Select one..."); ?></option>
                <?php 
do {  
?>
                <option value="<?php echo $row_rsDropdown['subsector']?>"<?php if (!(strcmp($row_rsDropdown['subsector'], $row_rshealth['subsector']))) {echo "SELECTED";} ?>><?php echo $row_rsDropdown['subsector']?></option>
                <?php
} while ($row_rsDropdown = mysql_fetch_assoc($rsDropdown));
  $rows = mysql_num_rows($rsDropdown);
  if($rows > 0) {
      mysql_data_seek($rsDropdown, 0);
	  $row_rsDropdown = mysql_fetch_assoc($rsDropdown);
  }
?>
              </select>
                <?php echo $tNGs->displayFieldError("health", "subsector", $cnt1); ?></p></td>
          </tr>
          <tr>
            <td class="KT_th"><label for="title_<?php echo $cnt1; ?>"><br />
            Title:</label></td>
            <td><p>
              <input type="text" name="title_<?php echo $cnt1; ?>" id="title_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rshealth['title']); ?>" size="32" maxlength="100" />
                <?php echo $tNGs->displayFieldHint("title");?> <?php echo $tNGs->displayFieldError("health", "title", $cnt1); ?></p></td>
          </tr>
          <tr>
            <td class="KT_th"><label for="description_<?php echo $cnt1; ?>"><br />
            Description:</label></td>
            <td><p>
              <textarea name="description_<?php echo $cnt1; ?>" id="description_<?php echo $cnt1; ?>" cols="50" rows="5"><?php echo KT_escapeAttribute($row_rshealth['description']); ?></textarea>
                <?php echo $tNGs->displayFieldHint("description");?> <?php echo $tNGs->displayFieldError("health", "description", $cnt1); ?></p></td>
          </tr>
          <tr>
            <td class="KT_th"><label for="location_<?php echo $cnt1; ?>"><br />
            Location:</label></td>
            <td><p>
              <input type="text" name="location_<?php echo $cnt1; ?>" id="location_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rshealth['location']); ?>" size="32" maxlength="100" />
                <?php echo $tNGs->displayFieldHint("location");?> <?php echo $tNGs->displayFieldError("health", "location", $cnt1); ?></p></td>
          </tr>
          <tr>
            <td class="KT_th"><label for="date_<?php echo $cnt1; ?>"><br />
            Date:</label></td>
            <td><p>
              <input type="text" name="date_<?php echo $cnt1; ?>" id="date_<?php echo $cnt1; ?>" value="<?php echo KT_formatDate($row_rshealth['date']); ?>" size="10" maxlength="22" />
                <?php echo $tNGs->displayFieldHint("date");?> <?php echo $tNGs->displayFieldError("health", "date", $cnt1); ?></p></td>
          </tr>
        </table>
        <input type="hidden" name="kt_pk_health_<?php echo $cnt1; ?>" class="id_field" value="<?php echo KT_escapeAttribute($row_rshealth['kt_pk_health']); ?>" />
        <?php } while ($row_rshealth = mysql_fetch_assoc($rshealth)); ?>
      <div class="KT_bottombuttons">
        <div>
          <?php 
      // Show IF Conditional region1
      if (@$_GET['newsid'] == "") {
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
<?php
mysql_free_result($rsDropdown);
?>
