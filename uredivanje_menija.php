<?php
include_once './baza_class.php';
include_once './sesija.class.php';
include_once './vrijeme.php';
if (!isset($_SESSION)) {
    session_start();
}
include_once './dnevnik.php';
dnevnik_unos();
$baza = new Baza();
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
        <script type="text/javascript" src="ispis_meni.js"></script>
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
            <table>
                <?php
                $upit = "select * from meni";
                $rezultat = $baza->select_upit($upit);
                $upit = "select * from moderatori";
                $rez = $baza->select_upit($upit);
                while ($r = mysqli_fetch_array($rez)) {
                    if ($r["korisnik"] == $_SESSION[Sesija::KORISNIK]["id"]) {
                        $upit = "select * from meni";
                        $rezultat = $baza->select_upit($upit);
                        while ($red = mysqli_fetch_array($rezultat)) {
                            if ($red["trenutno_stanje"] <= 10 && $red["trenutno_stanje"] != 0 && $red["menza"] == $r["menza"] && $red["datum"] == datum()) {
                                ?>
                                <tr><td>Meni:</td><td><a href="<?php echo "detalji_meni.php?id=".$red["id"];?>"><?php echo $red["naziv"]; ?></a></td></tr>
                                <tr><td>Opis:</td><td><a><?php echo $red["opis"]; ?></a></td></tr>
                                <tr><td>Količina:</td><td><a><?php echo $red["trenutno_stanje"]; ?></a></td></tr>
                            <?php
                            }
                            if ($red["trenutno_stanje"] == 0 && $red["menza"] == $r["menza"] && $red["datum"] == datum()) {
                                ?>
                                <tr><td>Meni:</td><td><a style="color: red"><?php echo $red["naziv"]; ?></a></td></tr>
                                <tr><td>Opis:</td><td><a style="color: red"><?php echo $red["opis"]; ?></a></td></tr>
                                <tr><td>Količina:</td><td><a style="color: red"><?php echo $red["trenutno_stanje"]; ?></a></td></tr>
                                <?php
                            }
                        }
                    }
                }
                ?>
            </table>

        </div>
    </body>
</html>