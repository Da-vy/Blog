<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>

<body>
<?php
//Database verbinding includeren.
include($_SERVER['DOCUMENT_ROOT'].'/051R7/connection.php');

//Error Handler includeren.
include($_SERVER['DOCUMENT_ROOT'].'/051R7/error_handler.php');

//Tabel users aanmaken
$query_users = "CREATE TABLE Users(
                UserID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                Username VARCHAR(20) NOT NULL,
                Password VARCHAR(32) NOT NULL,
                Email VARCHAR(50) NOT NULL
              )";

if(mysqli_query($conn, $query_users)){
  echo "Table Users created successfully";}
else{
  echo "Error creating table: " . mysqli_error($conn);}

//Tabel blog aanmaken
$query_blog = "CREATE TABLE Blog(
               BlogID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
               UserID INT NOT NULL,
               Onderwerp VARCHAR(50) NOT NULL,
               Tekst TEXT NOT NULL,
               Datum DATETIME
             )";

if(mysqli_query($conn, $query_blog)){
  echo "<BR>Table Blog created successfully";}
else{
  echo "<BR>Error creating table: " . mysqli_error($conn);}

?>
</body>

</html>