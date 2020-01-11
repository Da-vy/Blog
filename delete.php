<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>

<head>
<title></title>
</head>
<body>
<?php
//Starten van de sessie
session_start();

//Database verbinding includeren.
include($_SERVER['DOCUMENT_ROOT'].'/051R7/connection.php');

//Error Handler includeren.
include($_SERVER['DOCUMENT_ROOT'].'/051R7/error_handler.php');

//blogid uit home.php ophalen zodat de juiste blog verwijderd wordt.
$blogid = $_SESSION['blogid'];

$query_delete = "DELETE
                 FROM Blog
                 WHERE BlogID = $blogid";

                $result_delete = mysqli_query($conn, $query_delete);
                echo mysqli_error($conn);

                if(mysqli_affected_rows($conn) == 1)
                {
                  header("location: home.php");
                }
                else
                {
                  error_log(mysqli_error($conn));
                }





?>
</body>

</html>