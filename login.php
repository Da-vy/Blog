<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<?php
//Starten van de sessie
session_start();

//Database verbinding includeren.
include($_SERVER['DOCUMENT_ROOT'].'/051R7/connection.php');

//Error Handler includeren.
include($_SERVER['DOCUMENT_ROOT'].'/051R7/error_handler.php');

$message = "";

if(isset($_POST['submit'])){

    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    if ($username != "" && $password != ""){

        $sql_query = "SELECT count(*) as countUser
                      FROM users
                      WHERE username='".$username."' and password='".md5($password)."'
                     ";
        $result = mysqli_query($conn,$sql_query);
        $row = mysqli_fetch_array($result);

        $count = $row['countUser'];

        if($count > 0){
            $_SESSION['username'] = $username;
            header('Location: home.php');}
        else{
            $message = "Invalid username and password!"."<BR><BR>";}

    }

}

?>

<html>

<head>
<title>Login</title>
</head>
<body BGCOLOR="#F7F8FC">
<font face="verdana">
<br>
<center>
<table>
<form action="home.php">
<input type="submit" name="home" value="Home">
</form>
</table>
</center>
<br>
<table BORDER=1 CELLPADDING=10 WIDTH=100% BGCOLOR="#000000">
<tr>
<td ALIGN=CENTER VALIGN=TOP WIDTH=12% BGCOLOR="#003964">
<font color=white><br>
</font>
</td>
<td BGCOLOR="#ffffff" ALIGN=LEFT VALIGN=TOP WIDTH=50%>
<center>
<H1><img src="blog.jpg" width="213" height="101" border=3></H1>
<H2>Login</H2>
<?php
print $message;
?>
<table>
<form method="post" action="">
<TR><TD>Username:</TD><TD><input type="text" class="textbox" id="username" name="username"></TD></TR>
<TR><TD>Password:</TD><TD><input type="password" class="textbox" id="password" name="password"></TD></TR>
<TR><TD><TD align=Right>  <input type="submit" value="Submit" name="submit" id="submit"></TD></TR>
</form>
</table>
</center>
</td>
<td Align=CENTER VALIGN=TOP WIDTH=12% BGCOLOR="#003964">
<font color=white></font><br>
</td>
</tr>
</table>
<div style="position: absolute; bottom: 5px; ">
Davy de Jonge
</div>
</font>

</body>