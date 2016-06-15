<?php
include_once './baza_class.php';
$baza = new Baza();
$uredi = $_GET["uredi"];
if($uredi == "t"){
    $kod=$_GET["kod"];
    $upit = "select * from aktivacija_korisnika where kod='$kod'";
    $rezultat = $baza->select_upit($upit);
    $red=  mysqli_fetch_array($rezultat);
}
if(!empty($_POST)&&$uredi=="t"){
    $korisnik = $_POST["korisnik"];
    $div = $_POST["div"];
    $upit = "update aktivacija_korisnika set korisnik= '$korisnik', datum_i_vrijeme='$div' where kod='$kod';";
    $baza->ostali_upiti($upit);
    header("Location:postavke.php");
}
elseif(!empty($_POST)&&$uredi=="f"){
    $kod = $_POST["kod"];
    $korisnik = $_POST["korisnik"];
    $div = $_POST["div"];
    $upit = "insert into aktivacija_korisnika values('$kod','$korisnik','$div');";
    $baza->ostali_upiti($upit);
    header("Location:postavke.php");
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>MENZE-aplikacija</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="preglednik.css" rel="stylesheet" type="text/css">
        <script src='https://www.google.com/recaptcha/api.js'></script>


    </head>
    <body>
        <script type="text/javascript" src="provjera_registracije.js"></script>
        <header>
            <div class="naslov">MENZE</div>
        </header>

        

        <div class="sadrzaj">
            
            <a class="greske"><?php
                global $greska;
                echo $greska;
                ?></a>
            <form name="registracija" id="registracija" method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
                <table>
                    <tr><td>Kod:</td><td><input type="text" name="kod" id="kod" value="<?php if($uredi == "t"){ echo $red[0]; } ?>"></td></tr>
                    <tr><td>Korisnik:</td><td><input type="text" name="korisnik" id="korisnik" value="<?php if($uredi == "t"){ echo $red[1]; } ?>"></td></tr>
                    <tr><td>Datum i vrijeme:</td><td><input type="datetime" name="div" id="div" value="<?php if($uredi == "t"){ echo $red[2]; } ?>"></td></tr>
                    <tr><td><input type="submit" value="Potvrdi"></tr>
                </table>
            </form>

        </div>



    </body>

</html>