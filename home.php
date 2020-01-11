<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<?php
//Starten van de sessie
session_start();

//Database verbinding includeren.
include($_SERVER['DOCUMENT_ROOT'].'/051R7/connection.php');

//Error Handler includeren.
include($_SERVER['DOCUMENT_ROOT'].'/051R7/error_handler.php');

//Fucntie voor het tonen van een blog
function display_db_table($connection, $blogger)
{
  $query_string = "SELECT Onderwerp, Tekst, DATE_FORMAT(Datum, '%d-%m-%Y %k:%i'), BlogID
                   FROM blog
                   WHERE UserID = (SELECT UserID FROM users WHERE Username = '$blogger')
                   GROUP BY Datum DESC
                   ";
  $result_id = mysqli_query($connection, $query_string);
  $column_count = mysqli_num_fields($result_id);
  $id = mysqli_insert_id($connection);

  print("<TABLE BORDER=0 BGCOLOR='#000000'>\n");
  //print table headers
  print("<TR BGCOLOR='#FFFFFF'>");
  print("<TH>Onderwerp</TH>");
  print("<TH>Blogtekst</TH>");
  print("<TH>Datum</TH>");
  //laat kolom met de buttons bewerken en verwijderen alleen zien als de eigneaar van de blog
  //of als user admin is ingelogd.
  if(isset($_SESSION['username']) && ($_SESSION['username'] == $blogger || $_SESSION['username'] == "admin"))
  {
  print("<TH></TH>");
  }

  //print table body
  while($row = mysqli_fetch_array($result_id))
  {
  //laat de eerder opgevraagde kolommen zien, behalve de laatste (BlogID)
  echo("<TR ALIGN=LEFT VALIGN=TOP BGCOLOR='#FFFFFF'>");
             for($column_num = 0;
                 $column_num < ($column_count -1);
                 $column_num++)
            echo("<TD>".nl2br($row[$column_num])."</TD>\n");

             //Bewerken mogelijk maken wanneer gebruiker is ingelogd en zijn of haar eigen blog heeft geselecteerd
             //of wanneer admin is ingelogd.
             if(isset($_SESSION['username']) && ($_SESSION['username'] == $blogger || $_SESSION['username'] == "admin"))
             {
               //globale variabele maken zodat deze ook buiten de functie te benaderen is.
               //blogid aan sessie toekennen zodat deze ook door delete.php kan worden opgepakt.
               global $blogid;
               $blogid = $row[$column_count - 1];
               $_SESSION['blogid'] = $blogid;
               //button om blog te verwijderen. delete.php wordt uitgevoerd na klik.
               echo('<td align="center" valign="center">
                     <form action="delete.php">
                     <input type="submit" name="verwijderen" value="Verwijderen">
                     </form>');

            }
             print("</TR>\n");
  }
  //Button om nieuwe blog in te voeren.
  echo('<form method="Post" action="">
        <input type="submit" name="bewerken" value="Bewerken" >
        </form><BR><BR>');

  print("</TABLE>\n");


}

//Als onderwerp en boodschap gevuld zijn dan bijbehordende variabelen vullen
//Als ze niet gevuld zijn variabele leeglaten.
//(Dit om gegevens in de invulvelden te bewaren).
if(isset($_POST['onderwerp'])){
  $onderwerp = $_POST['onderwerp'];}
else{
  $onderwerp = "";}

if(isset($_POST['boodschap'])){
  $boodschap = $_POST['boodschap'];}
else{
  $boodschap = "";}

if (isset($_POST['bewerken']))
{

//HTML formulier toekennen aan variabele $aanmeldformulier.
//kan alleen als iemand is ingelogd en gemachtigd om te bewerken.
$blog_invoer = <<< EOINVOER
<FORM METHOD=post ACTION="home.php">
<table>
<TR><TD align="Center"><b>Onderwerp</b></TD></TR>
<TD align="Center"><input type="text" size=30 name="onderwerp" value="$onderwerp"></TD></TR>
<TR><TD align="Center"><BR><b>Blogtekst</b></TD></TR>
<TR><TD Align="Center"><TEXTAREA NAME="boodschap" ROWS=10 COLS=50>$boodschap</TEXTAREA></TD></TR>
<TR><TD ALIGN=RIGHT><BR><input type="submit" name="submit" value="Submit"></TD></TR>
</table>
</form>
EOINVOER;

}
//Als er niet op de knop bewerken wordt gedrukt blijft het formulier $blog_invoer onzichtbaar.
else
{
$blog_invoer = "";
}

//blogger ophalen uit cookie (zie regel 218)
//blogger wordt gebruikt om blogtekst de juiste userid te kunnen geven.
if(isset($_COOKIE['blogger']))
{
$blogger_invoer = $_COOKIE['blogger'];
}

if (isset($_POST['submit']) && $_POST['submit'] == 'Submit')
    {
     $query_toevoegen = "INSERT INTO blog (BlogID, UserID, Onderwerp, Tekst, Datum)
                         VALUES (NULL, (SELECT UserID FROM users WHERE Username = '$blogger_invoer'),
                                 '$onderwerp', '$boodschap', now())
                        ";
     $result_toevoegen = mysqli_query($conn, $query_toevoegen);

     if($result_toevoegen)
     {
         $blog_invoer = "Blogtekst is toegevoegd. Over enkele seconden ga je terug naar home.php";
         Header('refresh:5; /051R7/home.php');//redirect naar homepage na 5 seconden.
     }
     else
     {
     echo mysqli_error($conn);
     }
    }




?>
<html>

<head>
<title>Inzendopdracht 051R7</title>
</head>
<body BGCOLOR="#F7F8FC">
<font face="verdana">
<br>
<center>
<?php
//bij drukken op logout de sessie killen.
if(isset($_POST['logout'])){
    session_destroy();
    header('Location: home.php');}
//als iemand niet ingelogd is de buttons login en registreren laten zien.
if(!isset($_SESSION['username'])){
   echo('<table>
         <form action="login.php">
         <input type="submit" name="login" value="Login" >
         </form>
         &nbsp;
         <form action="users.php">
         <input type="submit" name="registreren" value="Registreren">
         </form>
         </table>');}
//als iemand is ingelogd de button logout laten zien.
if(isset($_SESSION['username'])){
   echo('<table>
         <form method="Post" action="">
         <input type="submit" name="logout" value="Logout" >
         </form>
         </table>');}

?>

</center>
<br>
<table BORDER=1 CELLPADDING=10 WIDTH=100% BGCOLOR="#000000">
<tr>
<td ALIGN=CENTER VALIGN=TOP WIDTH=12% BGCOLOR="#003964">
<font color=white><br>

<?php
//selecteer alle bloggers die op de database staan en voeg deze toe aan de array bloggers
$bloggers = array();
$query_bloggers = "SELECT Username
                   FROM users";

$result_bloggers = mysqli_query($conn, $query_bloggers);

while($row = mysqli_fetch_array($result_bloggers)){
           $bloggers[] = $row['Username'];}
//tel het aantal bloggers.
$aantal_bloggers = mysqli_num_rows($result_bloggers);

//Select string maken voor lijst met Bloggers
$select_str = "";
//door key op 1 te zetten blijft admin verborgen
$key = 1;
while($key < $aantal_bloggers){
         $select_str .= "<OPTION VALUE=\"$bloggers[$key]\">$bloggers[$key]</OPTION>";
          $key ++;}

?>

<form method=post action="">
<!-- overflow: auto haalt de scrollbar weg :) -->
<SELECT name="bloggers" style="background-color: #003964; border-color: white; color: white; overflow: auto" size=22>
<?php echo $select_str; ?>
</select>
<br><br><br>
<input type="submit" name="submit" value="Kies blogger">
</form>
<?php
//als er op de button kies blogger wordt gedrukt wordt de gekozen blogger
//toegevoegd aan variable blogger en een cookie gemaakt met deze blogger.
if(isset($_POST['bloggers']))
{
  $blogger = $_POST['bloggers'];
  setcookie('blogger', $blogger);
}
else
{
  $blogger = "";
}


?>
</font>
</td>
<td BGCOLOR="#ffffff" ALIGN=LEFT VALIGN=TOP WIDTH=50%>
<center>
<H1><img src="blog.jpg" width="213" height="101" border=3></H1>
<BR>

<?php
//als er geen blogger is geselecteerd onderstaand stukje uitleg printen
if(!$blogger && !$blog_invoer)
{
echo "Selecteer aan de linkerzijde van de pagina een blogger en druk
      op \"kies blogger\" om de blog van deze persoon te lezen.<BR><BR>";
}

//print het veld om een blog aan te passen.
echo $blog_invoer;


//print blog
if($blogger)
{
display_db_table($conn, $blogger);
}



?>

</center>
</td>
<td Align=CENTER VALIGN=TOP WIDTH=12% BGCOLOR="#003964">
<font color=white>
<?php
echo "Blogger:<br><br>$blogger";
?>
</font><br>
</td>
</tr>
</table>
<div style="position: absolute; bottom: 5px; ">
Davy de Jonge
</div>
</font>

</body>

</html>