<?php
include_once 'baza_class.php';
include_once './sesija.class.php';
include_once './dnevnik.php';
dnevnik_unos();
include_once './vrijeme.php';
if (!isset($_SESSION)) {
    session_start();
}
$baza = new Baza();
$like=$_GET["svida"];
$vrsta = $_GET["atr"];
$objekt = $_GET["id"];
if($vrsta == 0){
    $upit = "select count(*) from lajkovi where meni=$objekt and korisnik=".$_SESSION[Sesija::KORISNIK]["id"].";";
    
}
elseif($vrsta == 1){
    $upit = "select count(*) from lajkovi_menza where menza=$objekt and korisnik=".$_SESSION[Sesija::KORISNIK]["id"].";";

}
$rezultat=$baza->select_upit($upit);
$red = mysqli_fetch_array($rezultat);
$greska = "";
$vrijeme = datum();
if($like == 1 && $red[0] == 0){
    if($vrsta == 0){
        $upit = "update meni set svida = svida + 1 where id=".$objekt.";";
        $baza->ostali_upiti($upit);
        $upit = "insert into lajkovi(korisnik,meni,datum) values(".$_SESSION[Sesija::KORISNIK]["id"].",$objekt,'$vrijeme');";
        $baza->ostali_upiti($upit);
        header("Location:menze.php");
    }
    if($vrsta == 1){
        $upit = "update menze set svida = svida + 1 where id=".$objekt.";";
        $baza->ostali_upiti($upit);
        $upit = "insert into lajkovi_menza(korisnik,menza,datum) values(".$_SESSION[Sesija::KORISNIK]["id"].",$objekt,'$vrijeme');";
        $baza->ostali_upiti($upit);
        header("Location:menze.php");
    }
}
elseif ($like == 0 && $red[0] == 0) {
    if($vrsta == 0){
        $upit = "update meni set nesvida = nesvida + 1 where id=".$objekt.";";
        $baza->ostali_upiti($upit);
        $upit = "insert into lajkovi(korisnik,meni,datum) values(".$_SESSION[Sesija::KORISNIK]["id"].",$objekt,'$vrijeme');";
        $baza->ostali_upiti($upit);
        header("Location:menze.php");
    }
    if($vrsta == 1){
        $upit = "update menze set nesvida = nesvida + 1 where id=".$objekt.";";
        $baza->ostali_upiti($upit);
        $upit = "insert into lajkovi_menza(korisnik,menza,datum) values(".$_SESSION[Sesija::KORISNIK]["id"].",$objekt,'$vrijeme');";
        $baza->ostali_upiti($upit);
        header("Location:menze.php");
    }
}
 else {
    $greska.="Već ste lajkali.<br>";
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>MENZE-aplikacija</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="preglednik.css" rel="stylesheet" type="text/css">
    </head>
    <body>
        <script type="text/javascript" src="ispis_rezervacija.js"></script>
        <header>
            <div class="naslov">MENZE</div>
        </header>

        <nav><ul>
                <li><a href="index.php">Početna</a></li>
                <li><a href="menze.php">Menze</a></li>
            </ul></nav>
        <div class="sadrzaj">
            <?php if (Sesija::dajKorisnika() === null): ?>
                <div class="zaglavlje"><a href="prijava.php">Prijava</a><span>   </span><a href="registracija.php">Registracija</a></div>
            <?php endif; ?>
            <?php if (isset($_SESSION[Sesija::KORISNIK])): ?>
                <div class="zaglavlje"><a><?php echo $_SESSION[Sesija::KORISNIK]["korisnicko_ime"]; ?></a><span>   </span><a href="odjava.php">Odjava</a></div>
            <?php endif; ?>
                <?php echo $greska;?>
        </div>
    </body>
</html>