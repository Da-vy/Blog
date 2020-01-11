<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>

<?php
//Database verbinding includeren.
include($_SERVER['DOCUMENT_ROOT'].'/051R7/connection.php');

//Error Handler includeren.
include($_SERVER['DOCUMENT_ROOT'].'/051R7/error_handler.php');

//fucntie om te checken of een email adres aan de volgende voorwaarden voldoet:
//Het e-mailadres moet eindigen op ".nl"
//Er moet een @ in het adres staan.
//De naam en domeinnaam moeten uit minimaal twee karakters bestaan.
function correct_email($email){
  if(preg_match('#^[a-zA-Z]+@[a-zA-Z]+\.nl#',$email)){
    return TRUE;}
  else{
    return FALSE;}
}

//seeden
mt_srand((double)microtime() * 1000000);

//functie om een willekeurig karakter te creëren.
function random_char($string)
{
$length = strlen($string);
$position = mt_rand(0, $length - 1);
return($string[$position]);
}

//functie om een string te maken met willekeurige karakters.
//charset string zijn de karakters waaruit gekozen kan worden.
//lenght bepaald de lengte van de string.
function random_string($charset_string, $length)
{
$return_string = "";
for($x = 0; $x < $length; $x++)
$return_string .= random_char($charset_string);
return($return_string);
}

//karakter sets waar random karakters uit gekozen kunnen worden.
$charset = "abcdefghijklmnopqrstuvwxyz";
$special_chars = '!@#$%^&*(){}[]?';
$charset_upper = strtoupper($charset);

//6 Selecteer 6 letters, 1 speciaal teken en 1 hoofdletter.
$random_string = random_string($charset, 6);
$random_special = random_string($special_chars, 1);
$random_upper = random_string($charset_upper, 1);

//Mix de 8 karakters door elkaar. En ken deze toe aan variable $password.
$password = (str_shuffle("$random_string" . "$random_special" . "$random_upper")); //str_shuffle — Randomly shuffles a string

//checken of de velden gevuld zijn. Anders krijg je de foutmelding "Undefined index:"
if(isset($_POST['username'])){
  $username = $_POST['username'];}

if(isset($_POST['email'])){
  $email = $_POST['email'];}

//Checkt of username en email adres correct gevuld zijn nadat er op submit geklikt wordt.
//Indien alles correct is worden de gegevens in de tabel users geplaatst.
$message = "";
$message_user = "";

if(isset($_POST['submit']) && $_POST['submit'] == 'Submit'){
  if(isset($username) && $username != "" && (strlen($username) >= 3)){
    if((isset($email) && $email != "" && correct_email($email)) ||
       (!isset($email)) || ($email == "")){
           $query = "INSERT INTO users (Username, Password, Email)
                     VALUES ('$username',md5('$password'),'$email')
                    ";
           $result = mysqli_query($conn, $query);

           if(mysqli_affected_rows($conn) == 1){

           //email versturen wanneer user is aangemaakt
           $formsent = mail("$email",
                            'Inloggegevens Blog',
                            "Username: $username\r\n
                             Wachtwoord: $password\r\n
                            ",
                            "From: $email\r\nBounce-to: $email");

          if($formsent){
          $message_user = "<p> Beste blogger, Je account met username $username is succesvol aangemaakt.<BR>
                             Je ontvangt binnen enkele momenten een email met daarin jouw wachtwoord.";}

              $message = 'User is toegevoegd!<BR>
                          Je wordt over enkele seconden doorgeleid naar de hoofdpagina.<BR>
                          Gebeurt dit niet? Druk dan op home.';
              $username = "";
              $password = "";
              $email = "";
              Header('refresh:5; /051R7/home.php');}//redirect naar homepage na 5 seconden.

           else{
            error_log(mysqli_error($conn));
            $message = 'Het toevoegen van de user is mislukt';}
      }
      else{
        $message = "Vul een correct email adres in en eindigend op .nl";}
    }
  else{
    $message = "Vul een username in bestaande uit minimaal drie karakters";}
}


?>
<html>

<head>
<title>Users</title>
</head>
<body BGCOLOR="#F7F8FC">
<font face="verdana">
<br>
<center>
<form action='home.php'>
<input type="submit" name="home" value="Home">
</form>
</center>
<br>
<table BORDER=1 CELLPADDING=10 WIDTH=100% BGCOLOR="#000000">
<tr>
<td ALIGN=CENTER VALIGN=TOP WIDTH=12% BGCOLOR="#003964">
<font color=white>Kolom 1</font><br>
</td>
<td BGCOLOR="#ffffff" ALIGN=LEFT VALIGN=TOP WIDTH=50%>
<center>
<H1><img src="blog.jpg" width="213" height="101" border=3></H1>
<?php echo $message."<BR>";
      echo $message_user?>
<BR>
<TABLE>
<FORM METHOD="post" ACTION="">
<TR><TD>Username:</TD><TD><INPUT TYPE="text" SIZE="20" NAME="username" VALUE="<?php if (isset($username)) echo $username; ?>" ></TD></TR>
<TR><TD>Email:</TD><TD><INPUT TYPE="text" SIZE="20" NAME="email" VALUE="<?php if (isset($email)) echo $email; ?>" ></TD></TR>
<TR><TD><TD align=right><BR><INPUT TYPE="submit" NAME="submit" VALUE="Submit"></TD></TR>
</FORM>
</TABLE>
</center>
</td>
<td Align=CENTER VALIGN=TOP WIDTH=12% BGCOLOR="#003964">
<font color=white>Kolom 3</font><br>
</td>
</tr>
</table>
<div style="position: absolute; bottom: 5px; ">
Davy de Jonge
</div>
</font>

</body>

</html>


