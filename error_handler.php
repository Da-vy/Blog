<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>

<body>
<?php
//custom error message.
set_error_handler("error_msg");

//Error handler :)
function error_msg($err_type, $err_msg, $err_file, $err_line){
  echo "<table border=1 bordercolor=red><td>";
  echo "<div class 'errorMsg'>";
  echo "<b>Error:</b>";
  echo "<p>";
  echo "Er is een fout opgetreden! ";
  echo "</div>";
  echo "<div class='finePrint'>";
  echo "Error type: $err_type: $err_msg in $err_file " .
       "at line $err_line";
  echo "</div>";
  echo "</table></td><br>";
}
?>
</body>

</html>