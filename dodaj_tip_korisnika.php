<?php
include_once './baza_class.php';
$baza = new Baza();
$uredi = $_GET["uredi"];
if($uredi == "t"){
    $id = $_GET["id"];
    $upit = "select * from tipovi_korisnika where id=$id";
    $rezultat = $baza->select_upit($upit);
    $red = mysqli_fetch_array($rezultat);
}
if(!empty($_POST)&&$uredi=="t"){
    $naziv = $_POST["naziv"];
    $opis = $_POST["opis"];
    $upit = "update tipovi_korisnika set naziv = '$naziv' where id=$id;";
    $baza->ostali_upiti($upit);
    $upit = "update tipovi_korisnika set opis = '$opis' where id=$id;";
    $baza->ostali_upiti($upit);
   header("Location:postavke.php");
}
elseif(!empty($_POST)&&$uredi=="f"){
    $naziv = $_POST["naziv"];
    $opis = $_POST["opis"];
    $upit = "insert into tipovi_korisnika values (null,'$naziv','$opis');";
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
                    <?php if($uredi == "t"): ?>
                    <tr><td>ID:</td><td><?php echo $red[0]; ?></td></tr>
                    <?php endif; ?>
                    <tr><td>Naziv:</td><td><input type="text" name="naziv" id="naziv" value="<?php if($uredi == "t"){ echo $red[1]; } ?>"></td></tr>
                    <tr><td>Opis:</td><td><input type="text" name="opis" id="opis" value="<?php if($uredi == "t"){ echo $red[2]; } ?>"></td></tr>
                    <tr><td><input type="submit" value="Potvrdi"></tr>
                </table>
            </form>

        </div>



    </body>

</html>