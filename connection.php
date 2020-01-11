<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>
<body>
<?php
//Connectie met database dbLOI
$conn = mysqli_connect('localhost', 'root', '', 'dbLOI');

if(!$conn){
  die('<BR>Could not connect: ' . mysqli_connect_error());}

?>
</body>

</html>