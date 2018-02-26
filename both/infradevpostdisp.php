<?php


mysql_connect('localhost','root','');

mysql_select_db('msids');

$sql='SELECT * FROM infradev_post';

$records=mysql_query($sql);

?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>DEV-POSTINGS</title>
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
	margin-left: 90px;
	}
	
tr:nth-child(even) {
	
	background-color: lightgrey;

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
<style type="text/css">
<!--
a {
	font-size: 12px;
}
-->
</style></head>

<body>
<p class="header"><strong>MSIDS DEVELOPMENT POSTINGS</strong></p>

<h1 class="intro_text">Welcome to MSIDS development postings section. Here you will be able to preview all the infrastructure projects in the Nairobi County.</h1>

<table width="1167" border="0" align="center" cellpadding="1" cellspacing="1" bordercolor="#FF3366" class="hui">
  <tr class="row">
    <th width="140">Development Name</th>
    <th width="435" height="32">Description</th>
    <th width="130">Client</th>
    <th width="74">Number</th>
    <th width="93">Email</th>
    <th width="99">Location</th>
    <th width="77">Start Date</th>
    <th width="94">End Date</th>
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
<p class="post"><a href="../superadmin/edit-infradevpost.php">Add a new project</a></p>
<p class="post"><a href="../superadmin/edit-infradevapp.php">Apply for a project</a></p>
</body>
</html>