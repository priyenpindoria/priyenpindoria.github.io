<?php


mysql_connect('localhost','root','');

mysql_select_db('msids');

$sql='SELECT * FROM jobs_post';

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
$ins_jobs_post->registerTrigger("END", "Trigger_Default_Redirect", 99, "jobpostmodal.php");
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
$upd_jobs_post->registerTrigger("END", "Trigger_Default_Redirect", 99, "jobpostmodal.php");
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
$rsjobs_post = $tNGs->getRecordset("jobs_post");
$row_rsjobs_post = mysql_fetch_assoc($rsjobs_post);
$totalRows_rsjobs_post = mysql_num_rows($rsjobs_post);

// Get the transaction recordset
$rscustom = $tNGs->getRecordset("custom");
$row_rscustom = mysql_fetch_assoc($rscustom);
$totalRows_rscustom = mysql_num_rows($rscustom);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>MSIDS Job Postings</title>
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
<p class="header"><strong>MSIDS JOB POSTINGS</strong></p>

<h1 class="intro_text">Welcome to MSIDS job posting section. Here you will be able to preview the job vacancies in the Nairobi County and apply for one.</h1>
<input type="text" id="myInput" onkeyup="myFunction()" placeholder="Search for A Post..." title="Type in a name">
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


<table width="1061" border="0" align="center" cellpadding="3" cellspacing="3" bordercolor="#FF3366" class="hui" id="myTable">
  <tr class="row">
    <th width="130" onclick="sortTable(0)">Job Name</th>
    <th width="170" height="32" onclick="sortTable(1)">Company</th>
    <th width="85" onclick="sortTable(2)">Number</th>
    <th width="129" onclick="sortTable(3)">Email</th>
    <th width="189" onclick="sortTable(4)">Location</th>
    <th width="133" onclick="sortTable(5)">Timings</th>
    <th width="91" onclick="sortTable(6)">Salary</th>
    <th width="109" onclick="sortTable(7)">Deadline</th>
  </tr>
  <?php
while($jobpost=mysql_fetch_assoc($records)) {

 echo "<tr>";
	
	echo "<td>".$jobpost['jobname']."</td>";
	echo "<td>".$jobpost['company']."</td>";
	echo "<td>".$jobpost['number']."</td>";
	echo "<td>".$jobpost['email']."</td>";
	echo "<td>".$jobpost['location']."</td>";
	echo "<td>".$jobpost['timings']."</td>";
	echo "<td>".$jobpost['salary']."</td>";
	echo "<td>".$jobpost['deadline']."</td>";
	
	echo "</tr>";

}

?>
</table>

<button class="post" onclick="document.getElementById('01').style.display='block'">Add A New Job</button>
<button class="post"><a style="color: black;" href="../superadmin/edit-jobapp.php">Apply For A job</a></button>
<button class="post"><a style="color: black;" href="flag_posts.php">Report A Post</a></button>

<div class="modal" id="01">
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
</div>
</body>
</html>