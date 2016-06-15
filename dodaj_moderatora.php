<?php
include_once 'baza_class.php';
include_once 'vrijeme.php';
include_once './dnevnik.php';
dnevnik_unos();
$baza = new Baza();
$uredi = $_GET["uredi"];
$k="";
$m="";
if ($uredi == "t") {
    $k = $_GET["k"];
    $m = $_GET["m"];
}
if (!empty($_POST)) {
    $korisnik = $_POST["korisnik"];
    $menza = $_POST["menza"];
    if ($uredi == "f") {
        $upit = "insert into moderatori values ($korisnik,$menza);";
        $baza->ostali_upiti($upit);
    }

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
                    <tr><td>Korisnik:</td><td><select id="korisnik" name="korisnik"><option></option>
                                <?php $upit = "select id, ime, prezime from korisnici;";
                                $rezultat = $baza->select_upit($upit);
                                ?>
                                <?php while ($red = mysqli_fetch_array($rezultat)): ?>
                                    <option value="<?php echo $red[0] ?>" <?php if ($red[0] == $k && $uredi =="t") {
                                    echo 'selected="selected"';
                                } ?>><?php echo $red[1] . " " . $red[2]; ?></option>
                                <?php endwhile; ?>
                            </select></td></tr>
                    <tr><td>Menza:</td><td><select id="mezna" name="menza">
                                <option></option>
                                <?php
                                $upit = "select id,naziv from menze;";
                                $rezultat = $baza->select_upit($upit);
                                ?>
<?php while ($red = mysqli_fetch_array($rezultat)): ?>
                                    <option value="<?php echo $red[0] ?>" <?php if ($red[0] == $m && $uredi =="t") {
        echo 'selected="selected"';
    } ?>><?php echo $red[1]; ?></option>
<?php endwhile; ?>
                            </select></td></tr>
                    <tr><td><input type="submit" value="Potvrdi"></tr>
                </table>
            </form>
        </div>



    </body>

</html>