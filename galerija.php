<?php
include_once 'sesija.class.php';
include_once './baza_class.php';
include_once './dnevnik.php';
dnevnik_unos();
if (!isset($_SESSION)) {
    session_start();
}

$baza = new Baza();
if (Sesija::dajKorisnika() === null) {
    header("Location:prijava.php");
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
        <script type="text/javascript" src="ispis_slika.js"></script>
        <script type="text/javascript" src="trazena_slika.js"></script>
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
            <?php if (isset($_SESSION[Sesija::KORISNIK])): ?>
                <div class="zaglavlje"><a><?php echo $_SESSION[Sesija::KORISNIK]["korisnicko_ime"]; ?></a><span>   </span><a href="odjava.php">Odjava</a></div>
                <?php endif; ?>
            <form action="upload.php" method="post" enctype="multipart/form-data">
                Odaberi sliku:
                <input type="file" name="fileToUpload" id="fileToUpload"><br>
                Tag:<input type="text" id="tag" name="tag">*tagovi se odvajaju s ';'<br>
                <input type="submit" value="Spremi sliku" name="submit">
            </form><br>
            <input type="submit" id="ispis" name="ispis" onclick="javascript:dajPodatke()" value="Prikaži slike"><br>
            Tag:   <input type="text" onchange="tagSlika(this)">
            <div id="cilj"></div>
        </div>
    </body>
</html>
