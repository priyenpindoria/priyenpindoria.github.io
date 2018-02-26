<?php


mysql_connect('localhost','root','');

mysql_select_db('msids');

$sql='SELECT * FROM infradev_app';

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
$restrict->Execute();
//End Restrict Access To Page

// Start trigger
$formValidation = new tNG_FormValidation();
$formValidation->addField("project", true, "text", "", "", "", "");
$formValidation->addField("developer_company", true, "text", "", "", "", "");
$formValidation->addField("company", true, "text", "", "", "", "");
$formValidation->addField("cost", true, "text", "", "", "", "");
$formValidation->addField("startdate", true, "date", "date", "", "", "");
$formValidation->addField("enddate", true, "date", "date", "", "", "");
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
$query_companies = "SELECT * FROM companies";
$companies = mysql_query($query_companies, $connMISDS) or die(mysql_error());
$row_companies = mysql_fetch_assoc($companies);
$totalRows_companies = mysql_num_rows($companies);

mysql_select_db($database_connMISDS, $connMISDS);
$query_project = "SELECT * FROM infradev_post";
$project = mysql_query($query_project, $connMISDS) or die(mysql_error());
$row_project = mysql_fetch_assoc($project);
$totalRows_project = mysql_num_rows($project);

// Make an insert transaction instance
$ins_infradev_app = new tNG_multipleInsert($conn_connMISDS);
$tNGs->addTransaction($ins_infradev_app);
// Register triggers
$ins_infradev_app->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Insert1");
$ins_infradev_app->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$ins_infradev_app->registerTrigger("END", "Trigger_Default_Redirect", 99, "infradevappmodal.php");
// Add columns
$ins_infradev_app->setTable("infradev_app");
$ins_infradev_app->addColumn("project", "STRING_TYPE", "POST", "project");
$ins_infradev_app->addColumn("developer_company", "STRING_TYPE", "POST", "developer_company");
$ins_infradev_app->addColumn("company", "STRING_TYPE", "POST", "company");
$ins_infradev_app->addColumn("cost", "STRING_TYPE", "POST", "cost");
$ins_infradev_app->addColumn("startdate", "DATE_TYPE", "POST", "startdate");
$ins_infradev_app->addColumn("enddate", "DATE_TYPE", "POST", "enddate");
$ins_infradev_app->setPrimaryKey("post_id", "NUMERIC_TYPE");

// Make an update transaction instance
$upd_infradev_app = new tNG_multipleUpdate($conn_connMISDS);
$tNGs->addTransaction($upd_infradev_app);
// Register triggers
$upd_infradev_app->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Update1");
$upd_infradev_app->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$upd_infradev_app->registerTrigger("END", "Trigger_Default_Redirect", 99, "infradevappmodal.php");
// Add columns
$upd_infradev_app->setTable("infradev_app");
$upd_infradev_app->addColumn("project", "STRING_TYPE", "POST", "project");
$upd_infradev_app->addColumn("developer_company", "STRING_TYPE", "POST", "developer_company");
$upd_infradev_app->addColumn("company", "STRING_TYPE", "POST", "company");
$upd_infradev_app->addColumn("cost", "STRING_TYPE", "POST", "cost");
$upd_infradev_app->addColumn("startdate", "DATE_TYPE", "POST", "startdate");
$upd_infradev_app->addColumn("enddate", "DATE_TYPE", "POST", "enddate");
$upd_infradev_app->setPrimaryKey("post_id", "NUMERIC_TYPE", "GET", "post_id");

// Make an instance of the transaction object
$del_infradev_app = new tNG_multipleDelete($conn_connMISDS);
$tNGs->addTransaction($del_infradev_app);
// Register triggers
$del_infradev_app->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Delete1");
$del_infradev_app->registerTrigger("END", "Trigger_Default_Redirect", 99, "infradevappmodal.php");
// Add columns
$del_infradev_app->setTable("infradev_app");
$del_infradev_app->setPrimaryKey("post_id", "NUMERIC_TYPE", "GET", "post_id");

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
$rsinfradev_app = $tNGs->getRecordset("infradev_app");
$row_rsinfradev_app = mysql_fetch_assoc($rsinfradev_app);
$totalRows_rsinfradev_app = mysql_num_rows($rsinfradev_app);

// Get the transaction recordset
$rscustom = $tNGs->getRecordset("custom");
$row_rscustom = mysql_fetch_assoc($rscustom);
$totalRows_rscustom = mysql_num_rows($rscustom);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>MSIDS Development Applicant</title>
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
	margin-left: 600px;
	text-align: center;
	font-family: Arial, Helvetica, sans-serif;
	color: #000;
	margin-top: 10px;
	margin-bottomL 10px;
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

<p class="header"><strong>MSIDS DEVELOPMENT APPLICANTS</strong></p>

<h1 class="intro_text">Welcome to MSIDS infrastructure applicants section. Here you will be able to view all the applicant of the development posted.</h1>
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

<table width="1074" border="0" cellpadding="1" cellspacing="1" bordercolor="#FF3366" class="hui" id="myTable">
  <tr class="row">
    <th width="181" onclick="sortTable(0)">Project</th>
    <th width="245" height="32" onclick="sortTable(1)">Development Company</th>
    <th width="220" onclick="sortTable(2)">Construction Company</th>
    <th width="130" onclick="sortTable(3)">Cost</th>
    <th width="108" onclick="sortTable(4)">Start Date</th>
    <th width="171" onclick="sortTable(5)">Completion Date</th>

  </tr>
  <?php
while($post=mysql_fetch_assoc($records)) {

 echo "<tr>";
	
	echo "<td>".$post['project']."</td>";
	echo "<td>".$post['developer_company']."</td>";
	echo "<td>".$post['company']."</td>";
	echo "<td>".$post['cost']."</td>";
	echo "<td>".$post['startdate']."</td>";
	echo "<td>".$post['enddate']."</td>";

	
	echo "</tr>";

}

?>
</table>
<button class="post" onclick="document.getElementById('01').style.display='block'">Add A New Project</button>
<button class="post"><a href="flag_posts.php">Report A Post</a></button>


<div class="modal" id="01">
<?php
	echo $tNGs->getErrorMsg();
?>
<div class="KT_tng">
  <h1  style="text-align: center;">
    <?php 
// Show IF Conditional region1 
if (@$_GET['post_id'] == "") {
?>
      <?php echo NXT_getResource("Insert_FH"); ?>
      <?php 
// else Conditional region1
} else { ?>
      <?php echo NXT_getResource("Update_FH"); ?>
      <?php } 
// endif Conditional region1
?>
    A Development Application</h1>
  <div class="KT_tngform" style="margin-left: 400px; border-radius: 50px; padding: 20px;">
    <form method="post" id="form1" action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>">
      <?php $cnt1 = 0; ?>
      <?php do { ?>
        <?php $cnt1++; ?>
        <?php 
// Show IF Conditional region1 
if (@$totalRows_rsinfradev_app > 1) {
?>
          <h2><?php echo NXT_getResource("Record_FH"); ?> <?php echo $cnt1; ?></h2>
          <?php } 
// endif Conditional region1
?>
        <table cellpadding="2" cellspacing="0" class="KT_tngtable">
          <tr>
            <td width="143" class="KT_th"><label for="project_<?php echo $cnt1; ?>"><br />
            Project:</label></td>
            <td width="363"><p>
              <select name="project_<?php echo $cnt1; ?>" id="project_<?php echo $cnt1; ?>">
                <option value=""><?php echo NXT_getResource("Select one..."); ?></option>
                <?php 
do {  
?>
                <option value="<?php echo $row_project['devname']?>"<?php if (!(strcmp($row_project['devname'], $row_rsinfradev_app['project']))) {echo "SELECTED";} ?>><?php echo $row_project['devname']?></option>
                <?php
} while ($row_project = mysql_fetch_assoc($project));
  $rows = mysql_num_rows($project);
  if($rows > 0) {
      mysql_data_seek($project, 0);
	  $row_project = mysql_fetch_assoc($project);
  }
?>
              </select>
                <?php echo $tNGs->displayFieldError("infradev_app", "project", $cnt1); ?></p></td>
          </tr>
          <tr>
            <td class="KT_th"><label for="developer_company_<?php echo $cnt1; ?>"><br />
            Developer company:</label></td>
            <td><p>
              <select name="developer_company_<?php echo $cnt1; ?>" id="developer_company_<?php echo $cnt1; ?>">
                <option value=""><?php echo NXT_getResource("Select one..."); ?></option>
                <?php 
do {  
?>
                <option value="<?php echo $row_project['devcompany']?>"<?php if (!(strcmp($row_project['devcompany'], $row_rsinfradev_app['developer_company']))) {echo "SELECTED";} ?>><?php echo $row_project['devcompany']?></option>
                <?php
} while ($row_project = mysql_fetch_assoc($project));
  $rows = mysql_num_rows($project);
  if($rows > 0) {
      mysql_data_seek($project, 0);
	  $row_project = mysql_fetch_assoc($project);
  }
?>
              </select>
                <?php echo $tNGs->displayFieldError("infradev_app", "developer_company", $cnt1); ?></p></td>
          </tr>
          <tr>
            <td class="KT_th"><label for="company_<?php echo $cnt1; ?>"><br />
            Your Company:</label></td>
            <td><p>
              <select name="company_<?php echo $cnt1; ?>" id="company_<?php echo $cnt1; ?>">
                <option value="" <?php if (!(strcmp("", $row_rsinfradev_app['company']))) {echo "selected=\"selected\"";} ?>><?php echo NXT_getResource("Select one..."); ?></option>
                <?php
do {  
?>
                <option value="<?php echo $row_companies['companyname']?>"<?php if (!(strcmp($row_companies['companyname'], $row_rsinfradev_app['company']))) {echo "selected=\"selected\"";} ?>><?php echo $row_companies['companyname']?></option>
                <?php
} while ($row_companies = mysql_fetch_assoc($companies));
  $rows = mysql_num_rows($companies);
  if($rows > 0) {
      mysql_data_seek($companies, 0);
	  $row_companies = mysql_fetch_assoc($companies);
  }
?>
              </select>
                <?php echo $tNGs->displayFieldError("infradev_app", "company", $cnt1); ?></p></td>
          </tr>
          <tr>
            <td class="KT_th"><label for="cost_<?php echo $cnt1; ?>"><br />
            Cost:</label></td>
            <td><p>
              <input type="text" name="cost_<?php echo $cnt1; ?>" id="cost_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsinfradev_app['cost']); ?>" size="32" maxlength="100" />
                <?php echo $tNGs->displayFieldHint("cost");?> <?php echo $tNGs->displayFieldError("infradev_app", "cost", $cnt1); ?></p></td>
          </tr>
          <tr>
            <td class="KT_th"><label for="startdate_<?php echo $cnt1; ?>"><br />
            Date of Start:</label></td>
            <td><p>
              <input type="text" name="startdate_<?php echo $cnt1; ?>" id="startdate_<?php echo $cnt1; ?>" value="<?php echo KT_formatDate($row_rsinfradev_app['startdate']); ?>" size="10" maxlength="22" />
                <?php echo $tNGs->displayFieldHint("startdate");?> <?php echo $tNGs->displayFieldError("infradev_app", "startdate", $cnt1); ?></p></td>
          </tr>
          <tr>
            <td class="KT_th"><label for="enddate_<?php echo $cnt1; ?>"><br />
            Date of Completion:</label></td>
            <td><p>
              <input type="text" name="enddate_<?php echo $cnt1; ?>" id="enddate_<?php echo $cnt1; ?>" value="<?php echo KT_formatDate($row_rsinfradev_app['enddate']); ?>" size="10" maxlength="22" />
                <?php echo $tNGs->displayFieldHint("enddate");?> <?php echo $tNGs->displayFieldError("infradev_app", "enddate", $cnt1); ?></p></td>
          </tr>
        </table>
        <input type="hidden" name="kt_pk_infradev_app_<?php echo $cnt1; ?>" class="id_field" value="<?php echo KT_escapeAttribute($row_rsinfradev_app['kt_pk_infradev_app']); ?>" />
        <?php } while ($row_rsinfradev_app = mysql_fetch_assoc($rsinfradev_app)); ?>
      <div class="KT_bottombuttons" style="text-align:center;">
<button style="margin-bottom: 10px; margin-top: 2px;"> <a href="infradevpostmodal.php">Return To Development Postings Page</a> </button>

        <div>
          <?php 
      // Show IF Conditional region1
      if (@$_GET['post_id'] == "") {
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
mysql_free_result($companies);

mysql_free_result($project);
?>
