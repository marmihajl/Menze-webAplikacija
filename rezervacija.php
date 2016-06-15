<?php
include_once 'sesija.class.php';
include_once 'vrijeme.php';
$poruka = "";
if (!isset($_SESSION)) {
    session_start();
}
include_once './dnevnik.php';

if (Sesija::dajKorisnika() === null) {
    header("Location: prijava.php");
}
include_once 'baza_class.php';
$baza = new Baza();
if (!empty($_GET)) {
    $id = $_GET["id"];
    $upit = "select * from meni where id = " . $id . ";";
    $rezultat = $baza->select_upit($upit);
    $meni = mysqli_fetch_array($rezultat);
    $upit = "select * from menze where id = " . $meni["menza"] . ";";
    $rezultat2 = $baza->select_upit($upit);
    $menza = mysqli_fetch_array($rezultat2);
} else {
    header("Location: menze.php");
}
if (!empty($_POST)) {
    $m = $meni["id"];
    $korisnik = $_SESSION[Sesija::KORISNIK]["id"];
    $kolicina = $_POST["kolicina"];
    $sat = $_POST["vrijeme"];
    $datum = $meni["datum"];
    $vrijeme = $sat;
    $greska = false;
    $upit = "select menza from meni where id=$m";
    $rez = $baza->select_upit($upit);
    $menz = mysqli_fetch_array($rez);
    $sad = datum();
    $upit2 = "select * from crne_liste where korisnik=$korisnik and datum_zavrsetka = '".$sad."';";
    $rezultat = $baza->select_upit($upit2);
    while ($crna = mysqli_fetch_array($rezultat)) {
        if ($crna["menza"] == $menz[0]) {
            $greska = true;
            $poruka .= "Korisnik se nalazi na crnoj listi za odabranu menzu i u njoj ne može rezervirati meni.<br>";
        }
    }


    if ($kolicina === "" || $_POST["vrijeme"] === "") {
        $greska = true;
        $poruka .= "Sva polja moraju biti popunjena<br>";
    }
    if ($kolicina > 2) {
        $greska = true;
        $poruka .= "Jedan student smije rezervirati najviše dva menija.<br>";
    }
    if ($meni["trenutno_stanje"] == 0) {
        $greska = true;
        $poruka .= "Odabranog menija nema na raspolaganju i nije ga moguće rezervirati<br>";
    }
    $upit = "insert into rezervacije values($m, $korisnik, $kolicina,'$datum', '$vrijeme', default, default);";
    if ($greska === false) {
        if (!$baza->ostali_upiti($upit)) {
            $poruka .= "Došlo je do greške prilikom izvršavanja upita<br>";
        }
        header("Location: index.php");
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
    </head>
    <body>
        <header>
            <div class="naslov">MENZE</div>
        </header>

        <nav><ul>
                <li><a href="index.php">Početna</a></li>
                <li><a href="menze.php">Menze</a></li>
                <?php if(Sesija::dajKorisnika() != null): ?>
                <li><a href="galerija.php">Galerija</a></li>
                <?php endif;?>
                <?php if(Sesija::dajKorisnika() != null): ?>
                <?php if($_SESSION[Sesija::KORISNIK]["tip_korisnika"]<3): ?>
                <li><a href="pregled_rezervacija.php">Pregled rezervacija</a></li>
                <?php endif;?>
                <?php endif;?>
                <?php if(Sesija::dajKorisnika() != null): ?>
                <?php if($_SESSION[Sesija::KORISNIK]["tip_korisnika"]<2): ?>
                <li><a href="postavke.php">Postavke sustava</a></li>
                <li><a href="posjecenost.php">Korištenje sustava</a></li>
                <?php endif;?>
                <?php endif;?>
            </ul></nav>
        <div class="sadrzaj">
            <?php if (Sesija::dajKorisnika() === null): ?>
                <div class="zaglavlje"><a href="prijava.php">Prijava</a><span>   </span><a href="registracija.php">Registracija</a></div>
            <?php endif; ?>
            <?php if (isset($_SESSION[Sesija::KORISNIK])): ?>
                <div class="zaglavlje"><a><?php echo $_SESSION[Sesija::KORISNIK]["korisnicko_ime"]; ?></a><span>   </span><a href="odjava.php">Odjava</a></div>
            <?php endif; ?>
            <?php echo $poruka; ?>
            <div id="tekst">
                <form name="rezervacija" method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
                    <table>
                        <?php
                        global $menza;
                        global $meni;
                        include_once './vrijeme.php';
                        echo '<tr><td>Menza:</td><td>' . $menza["naziv"] . '</td></tr>';
                        echo '<tr><td>Naziv menija:</td><td>' . $meni["naziv"] . '</td></tr>';
                        echo '<tr><td>Opis menija:</td><td>' . $meni["opis"] . '</td></tr>';
                        echo '<tr><td>Datum:</td><td>' . $meni["datum"] . '</td></tr>';
                        echo '<tr><td>Vrijeme:</td><td><input type="time" id="vrijeme" name="vrijeme"></td></tr>';
                        echo '<tr><td>Količina koju želite rezervirati:</td><td><input type="text" size=2 id="kolicina" name="kolicina"></td></tr>';

                        echo '<tr><td><input type="submit" id="potvrda" name="potvrda" value="Rezerviraj"></td></tr>';
                        ?>
                    </table>
                </form>
                <input type="date" name="datum" id="datum" value="<?php echo datum(); ?>" style="visibility: hidden;display: none;">
            </div>
        </div>
    </body>
</html>