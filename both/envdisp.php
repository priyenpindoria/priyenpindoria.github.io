<?php


mysql_connect('localhost','root','');

mysql_select_db('msids');

$sql='SELECT * FROM environment';

$records=mysql_query($sql);

?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ENVIRONMENT</title>
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
	border-bottom-color: #7E80B4;
	margin-left: 10px;
	margin-right: 10px;
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
	
.intro_text {
	
	font-size: 12px;
	color:#000;
	padding-bottom: 1px;
	text-align: center;
	margin-bottom: 10px;
	
	}	

table {
	border: solid 0px #090;
	margin-left: 130px;
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
.container {
	background-color: #48EAE6;
	}

-->
</style>
<link href="../CSS/Level3_2.css" rel="stylesheet" type="text/css" />
</head>

<body>
<p class="header"><strong>MSIDS ENVIRONMENT</strong></p>

<h1 class="intro_text">Welcome to MSIDS environment section. Here you will be able to view all information on environment conservation within Nairobi County.</h1>

<table width="1070" align="center" cellpadding="5" cellspacing="1"  class="hui">
  <tr>
    <th width="175" class="height">Title</th>
    <th width="609" class="height">Description</th>
    <th width="147" class="height">Location</th>
    <th width="94" class="height">Date</th>
    <th>Sub Sector</th>

  </tr>
  <?php
while($env=mysql_fetch_assoc($records)) {

 echo "<tr>";
	
	echo "<td>".$env['environname']."</td>";
	echo "<td>".$env['environdesc']."</td>";
	echo "<td>".$env['environloca']."</td>";
	echo "<td>".$env['environdate']."</td>";
	echo "<td>".$env['envsubsec']."</td>";
	
	
	echo "</tr>";

}

?>
</table>
<p class="post"><a href="../superadmin/edit-env.php">Add a new sector</a></p>
</body>
</html>