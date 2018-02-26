<?php


mysql_connect('localhost','root','');

mysql_select_db('msids');

$sql='SELECT * FROM jobs_app';

$records=mysql_query($sql);

?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JOB APPLICANT</title>
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
	font-family: Arial, Helvetica, sans-serif;
	color: #000;
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


tr:nth-child(even) {
	
	background-color: lightgrey;

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


-->
</style>
<link href="../CSS/Level3_2.css" rel="stylesheet" type="text/css" />
</head>

<body>
<p class="header"><strong>MSIDS JOB APPLICANTS</strong></p>

<h1 class="intro_text">Welcome to MSIDS job pplicant section. Here you will be able to view all the job applicants in the Nairobi County.</h1>

<table width="1042" align="center" cellpadding="5" cellspacing="1"  class="hui" border="0">
  <tr>
    <th width="125" class="height"> Appicant Name</th>
    <th width="77" class="height">Job</th>
    <th width="63" class="height">Company</th>
    <th width="73" class="height">ID Number</th>
    <th width="74" class="height">Nationality</th>
    <th width="263" class="height">Previous Experiences</th>
    <th width="102" class="height">Date of Birth</th>
    <th width="81" class="height">Number</th>
    <th width="84" class="height">Email</th>
  </tr>
  <?php
while($jobapp=mysql_fetch_assoc($records)) {

 echo "<tr>";
	
	echo "<td>".$jobapp['applicantname']."</td>";
	echo "<td>".$jobapp['jobid']."</td>";
	echo "<td>".$jobapp['jobcompany']."</td>";
	echo "<td>".$jobapp['idnumber']."</td>";
	echo "<td>".$jobapp['Nationality']."</td>";
	echo "<td>".$jobapp['prevexperiences']."</td>";
	echo "<td>".$jobapp['dob']."</td>";
	echo "<td>".$jobapp['number']."</td>";
	echo "<td>".$jobapp['email']."</td>";
	
	echo "</tr>";

}

?>
</table>
<p class="post"><a href="../superadmin/edit-jobapp.php">Apply for a job </a></p>
</body>
</html>