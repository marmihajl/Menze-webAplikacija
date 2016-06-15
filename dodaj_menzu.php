<?php
include_once 'baza_class.php';
include_once 'vrijeme.php';
include_once './dnevnik.php';
dnevnik_unos();
$baza = new Baza();
$uredi = $_GET["uredi"];
if (!empty($_POST) && $uredi == "f") {
    $naziv = $_POST["naziv"];
    $adresa = $_POST["adresa"];
    $upit = "insert into menze values (null,'$naziv','$adresa',default,default);";
    $baza->ostali_upiti($upit);
    header("Location:postavke.php");
}
if ($uredi == "t") {
    $id = $_GET["id"];

    $upit = "select * from menze where id=$id;";
    $rezultat = $baza->select_upit($upit);
    $red = mysqli_fetch_array($rezultat);
    if (!empty($_POST)) {
        $naziv = $_POST["naziv"];
        $adresa = $_POST["adresa"];
        $svida = $_POST["svida"];
        $nesvida = $_POST["nesvida"];
        $upit = "update menze set naziv='$naziv', adresa='$adresa', svida=$svida, nesvida=$nesvida where id=$id;";
        $baza->ostali_upiti($upit);
        header("Location:postavke.php");
    }
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
                    <?php if ($uredi == "t"): ?>
                        <tr><td>ID:</td><td><div><?php if ($uredi == "t") {
                        echo $red[0];
                    } ?></div></td></tr>
                    <?php endif; ?>
                    <tr><td>Naziv:</td><td><input type="text" id="naziv" name="naziv" value="<?php if ($uredi == "t") {
                        echo $red[1];
                    } ?>"></td></tr>
                    <tr><td>Adresa:</td><td><input type="text" id="adresa" name="adresa" value="<?php if ($uredi == "t") {
                        echo $red[2];
                    } ?>"></td></tr>
<?php if ($uredi == "t"): ?>
                        <tr><td>Sviđa:</td><td><input type="number" id="svida" name="svida" value="<?php if ($uredi == "t") {
        echo $red[3];
    } ?>"></td></tr>
                        <tr><td>Nesviđa:</td><td><input type="number" id="nesvida" name="nesvida" value="<?php if ($uredi == "t") {
        echo $red[4];
    } ?>"></td></tr>
<?php endif; ?>
                    <tr><td><input type="submit" value="Potvrdi"></tr>
                </table>
            </form>

        </div>



    </body>

</html>
