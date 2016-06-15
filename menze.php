<?php

if ($_SERVER["HTTPS"] != "on") {
    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
    exit();
}
include_once 'sesija.class.php';
include_once './vrijeme.php';
if (!isset($_SESSION)) {
    session_start();
}
include_once './dnevnik.php';
dnevnik_unos();
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
        <script type="text/javascript" src="meni/ispis_meni.js"></script>
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
                <?php if ($_SESSION[Sesija::KORISNIK]["tip_korisnika"] < 3): ?>
                    <form action="dodavanje_meni.php" method="post">
                        <input type="submit" value="Dodaj meni">
                    </form>
                <form action="uredivanje_menija.php" method="post">
                        <input type="submit" value="Uredi meni">
                    </form>
                <form action="statistika.php">
                    <input type="submit" value="Pregledaj statistiku">
                </form>
                <?php endif; ?>
            <?php endif; ?>
            <?php if (Sesija::dajKorisnika() === null): ?>
                Odaberi menzu:<select name="menze" onchange="javascript:dajPodatke(this, false)">
                    <option></option>
                    <?php
                    include_once './baza_class.php';
                    $baza = new Baza();
                    $upit = "select * from menze";
                    $rezultat = $baza->select_upit($upit);
                    while ($red = mysqli_fetch_array($rezultat)) {
                        echo '<option value=' . $red["id"] . '>' . $red["naziv"] . '</option>';
                    }
                    ?>
                <?php endif; ?>
            </select>
            <?php if (Sesija::dajKorisnika() !== null): ?>
                Odaberi menzu:<select name="menze" onchange="javascript:dajPodatke(this, true)">
                    <option></option>
                    <?php
                    include_once './baza_class.php';
                    $baza = new Baza();
                    $upit = "select * from menze";
                    $rezultat = $baza->select_upit($upit);
                    while ($red = mysqli_fetch_array($rezultat)) {
                        echo '<option value=' . $red["id"] . '>' . $red["naziv"] . '</option>';
                    }
                    ?>
                    
                <?php endif; ?>
            </select>
                <input id="datum" name="datum" type="date" value="<?php echo datum();?>" onchange="javascript:dajPodatke(this, true)"> 
                <div id="cilj"></div>
        </div>
    </body>
</html>