<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>


<style>
table {margin: auto; border: 1px black solid;}
tr {border-bottom: 1px solid blue;}
tr:nth-child(even) {background-color: grey;)

</style>

<body>
<?php

mysql_connect('localhost','root','');

mysql_select_db('msids');

$sql='SELECT * FROM health';

$records=mysql_query($sql);
?>

<table>
<tr><th>Sub-Sector</th><th>Title</th><th>Description</th><th>Date</th>

<?php

while($health=mysql_fetch_assoc($records)) {

	echo "<tr>";
	echo "<td>".$health['subsector']."</td>";
	echo "<td>".$health['title']."</td>";
	echo "<td>".$health['description']."</td>";
	echo "<td>".$health['date']."</td>";
	echo "</tr>";
	
	


}
?>


</table>

</body>
</html>