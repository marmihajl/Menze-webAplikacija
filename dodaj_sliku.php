<?php
include_once './baza_class.php';
$baza = new Baza();
$uredi = $_GET["uredi"];
if ($uredi == "t") {
    $korisnik2 = $_GET["k"];
    $slika2 = $_GET["s"];
    $upit = "select tagovi from slike where korisnik = $korisnik2 and slika = '$slika2'";
    $rezultat = $baza->select_upit($upit);
    $red = mysqli_fetch_array($rezultat);
    $tag2 = $red[0];
}
if (!empty($_POST) && $uredi == "t") {
    $korisnik = $_POST["korisnik"];
    $slika = $_POST["slika"];
    $tag = $_POST["tag"];
    $upit = "update slike set korisnik=$korisnik, slika = '$slika', tagovi='$tag' where korisnik=$korisnik2 and slika = '$slika2';";
    $baza->ostali_upiti($upit);
    header("Location:postavke.php");
} elseif (!empty($_POST) && $uredi == "f") {
    $korisnik = $_POST["korisnik"];
    $slika = $_POST["slika"];
    $tag = $_POST["tag"];
    $upit = "insert into slike values ($korisnik,'$slika','$tag');";
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
                    <tr><td>Korisnik:</td><td><select id="korisnik" name="korisnik">
                                <option></option>
                                <?php
                                $upit = "select id,ime,prezime from korisnici;";
                                $rez = $baza->select_upit($upit);
                                while ($r = mysqli_fetch_array($rez)) {
                                    ?>
                                    <option value="<?php echo $r[0]; ?>" <?php if ($uredi == "t" && $r[0] == $korisnik2) {
                                    echo 'selected="selected"';
                                } ?>><?php echo $r[1] . " " . $r[2]; ?></option>
<?php } ?>
                            </select></td></tr>
                    <tr><td>Slika:</td><td><input type="text" name="slika" id="slika" value="<?php if ($uredi == "t") {
    echo $slika2;
} ?>"></td></tr>
                    <tr><td>Tag:</td><td><input type="text" name="tag" id="tag" value="<?php if ($uredi == "t") {
    echo $tag2;
} ?>"></td></tr>
                    <tr><td><input type="submit" value="Potvrdi"></tr>
                </table>
            </form>

        </div>



    </body>

</html>