<?php
include_once 'sesija.class.php';
include_once './baza_class.php';
include_once './vrijeme.php';
include_once './dnevnik.php';
dnevnik_unos();
$baza = new Baza();
if (!isset($_SESSION)) {
    session_start();
}
$korisnik = "";
$korisnik2 = "";
if (!empty($_POST["korisnik"])) {
    $korisnik = $_POST["korisnik"];
}
if (!empty($_POST["korisnik2"])) {
    $korisnik2 = $_POST["korisnik2"];
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
            <?php
            $upit = "SELECT korisnik, akcija, count(*), datum from dnevnik GROUP by korisnik,akcija";
            $rezultat = $baza->select_upit($upit);
            ?>
            <form method="post" name="korisnik">
                Korisnik: <input type="text" id="korisnik" name="korisnik">
            </form>
                <table border="1" id="tablica">
                <tr>
                    <td>Korisnik</td><td>Stranica</td><td>Broj posjeta</td>
                </tr>
                <?php
                $datum_od = "";
                $datum_do = "";
                while ($red = mysqli_fetch_array($rezultat)) {
                    $ime = "";
                    if ($red[0] != null) {
                        $upit = "select ime, prezime from korisnici where id = $red[0];";
                        $rez = $baza->select_upit($upit);
                        $r = mysqli_fetch_array($rez);
                        $ime = $r[0] . " " . $r[1];
                    }
                    $pos = strpos($red[1], "/");

                    if ($korisnik != "" && $korisnik == $ime && $pos === false && $datum_od == "" && $datum_do == "") {
                        ?>
                        <tr>
                            <td><?php
                                if ($red[0] != null) {
                                    echo $r[0] . " " . $r[1];
                                } else {
                                    echo $red[0];
                                }
                                ?></td><td><?php echo $red[1]; ?></td><td><?php echo $red[2]; ?></td>
                        </tr>
                    <?php } elseif ((empty($_POST["korisnik"]) || $korisnik == "") && $pos === false && $datum_od == "" && $datum_do == "") {
                        ?>
                        <tr>
                            <td><?php
                                if ($red[0] != null) {
                                    echo $r[0] . " " . $r[1];
                                } else {
                                    echo $red[0];
                                }
                                ?></td><td><?php echo $red[1]; ?></td><td><?php echo $red[2]; ?></td>
                        </tr>
                        <?php
                    } elseif ($datum_od != "" && $datum_do != "" && $pos === false) {
                        if ($datum_od >= $red[3] && $datum_do <= $red[3]) {
                            ?>
                            <tr>
                                <td><?php
                                    if ($red[0] != null) {
                                        echo $r[0] . " " . $r[1];
                                    } else {
                                        echo $red[0];
                                    }
                                    ?></td><td><?php echo $red[1]; ?></td><td><?php echo $red[2]; ?></td>
                            </tr>

                            <?php
                        }
                    }
                }
                ?>
            </table><hr>
            
        </div>
    </body>
</html>