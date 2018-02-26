<?php


mysql_connect('localhost','root','');

mysql_select_db('msids');

$sql='SELECT * FROM infradev_app';

$records=mysql_query($sql);

?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>DEV-APPLICANTS</title>
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
	margin: auto;
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
<p class="header"><strong>MSIDS DEVELOPMENT APPLICANTS</strong></p>

<h1 class="intro_text">Welcome to MSIDS infrastructure applicants section. Here you will be able to view all the applicant of the development posted.</h1>

<table width="882" border="0" cellpadding="1" cellspacing="1" bordercolor="#FF3366" class="hui">
  <tr class="row">
    <th width="181">Project</th>
    <th width="176" height="32">Development Company</th>
    <th width="164">Construction Company</th>
    <th width="130">Cost</th>
    <th width="79">Start Date</th>
    <th width="133">Completion Date</th>

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
</body>
</html>