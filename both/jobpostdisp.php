<?php


mysql_connect('localhost','root','');

mysql_select_db('msids');

$sql='SELECT * FROM jobs_post';

$records=mysql_query($sql);

?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JOB POSTINGS</title>
<style type="text/css">
<!--
.jobdisp {
	padding: 3px;
	border-bottom-width: 1px;
	border-top-style: none;
	border-right-style: none;
	border-bottom-style: solid;
	border-left-style: none;
	border-bottom-color: #E03C1F;
}
.header {
	font-family: Georgia, "Times New Roman", Times, serif;
	font-size: 18px;
	color: #00F;
	text-align: center;
	border-bottom-width: thin;
	border-bottom-style: solid;
	border-bottom-color: #00F;
}
.post {
	border: 1px solid #CCC;
	width: 150px;
	margin-left: 600px;
	text-align: center;
	font-family: Georgia, "Times New Roman", Times, serif;
	color: #000;
	margin-bottom: 10px;
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
.jobdisp {
	border-bottom-width: thin;
	border-top-style: none;
	border-right-style: none;
	border-bottom-style: solid;
	border-left-style: none;
	border-bottom-color: #E0591F;
}
.jobdisp {
	border-top-width: 0px;
	border-right-width: 0px;
	border-bottom-width: thin;
	border-left-width: 0px;
	border-top-style: none;
	border-right-style: none;
	border-bottom-style: solid;
	border-left-style: none;
	border-bottom-color: #F69;
}
.jobdisp {
	margin-top: 30px;
}
.jobdisp {
	border-top-style: none;
	border-right-style: none;
	border-bottom-style: none;
	border-left-style: none;
}
.display {
	border-bottom-width: thin;
	border-bottom-style: solid;
	border-bottom-color: #F36;
}

.asd {
	
	border-bottom-width: thin;
	border-bottom-style: solid;
	border-bottom-color: #F36;
	
	}
.tablesas {
	border: thick solid #000;
}


.row {
	
	border-bottom: thick #000;
	
	}
	
	
.intro_text {
	
	font-size: 12px;
	color:#000;
	padding-bottom: 1px;
	margin-bottom: 10px;
	text-align: center;
	
	}	
	
table {
	border: solid 0px #090;
	margin-left: auto;
	
   
	}
	

tr:nth-child(even) {
	background-color: #D7D3D2;
	}


th {
	padding: 10px;
	background-color: #7E80B4;
	font-family: Georgia, "Times New Roman", Times, serif;
	font-size: 36px;
	font-weight: bold;
	}	

td {
	border-bottom: 2px solid #666;
	padding: 10px;
	
	}
tr:nth-child(even) {
	
	background-color: lightgrey;

	}		

-->
</style>
<link href="../CSS/Level3_2.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
a {
	font-size: 12px;
}
-->
</style></head>

<body>
<p class="header"><strong>MSIDS JOB POSTINGS</strong></p>

<h1 class="intro_text">Welcome to MSIDS job posting section. Here you will be able to preview the job vacancies in the Nairobi County and apply for one.</h1>

<table width="959" border="0" align="center" cellpadding="1" cellspacing="1" bordercolor="#FF3366" class="hui">
  <tr class="row">
    <th width="151">Job Name</th>
    <th width="201" height="32">Company</th>
    <th>Number</th>
    <th>Email</th>
    <th width="230">Location</th>
    <th width="157">Timings</th>
    <th width="94">Salary</th>
    <th width="107">Deadline</th>
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

<p class="post"><a href="../superadmin/editjobpost.php">Add a new job</a></p>
<p class="post"><a href="../superadmin/edit-jobapp.php">Apply for a job</a></p>
</body>
</html>