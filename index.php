<?php
include_once 'sesija.class.php';
include_once './baza_class.php';
$baza = new Baza();
if (!isset($_SESSION)) {
    session_start();
}
if (Sesija::dajKorisnika() === null) {
    header("Location:prijava.php");
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
        <script type="text/javascript" src="js/ispis_rezervacija.js"></script>
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
                <?php if(Sesija::dajKorisnika() != null): ?>
                <li><a href="dokumentacija.html">Dokumentacija</a></li>
                <li><a href="o_autoru.html">o_Autoru</a></li>
                <?php endif;?>
            </ul></nav>
        <div class="sadrzaj">
            <?php if (Sesija::dajKorisnika() === null): ?>
                <div class="zaglavlje"><a href="prijava.php">Prijava</a><span>   </span><a href="registracija.php">Registracija</a></div>
            <?php endif; ?>
            <?php if (isset($_SESSION[Sesija::KORISNIK])): ?>
                <div class="zaglavlje"><a><?php echo $_SESSION[Sesija::KORISNIK]["korisnicko_ime"]; ?></a><span>   </span><a href="odjava.php">Odjava</a></div>
            <?php endif; ?>

            Vaše crne liste<br>
            <?php
            $k = $_SESSION[Sesija::KORISNIK]["id"];
            $upit = "select * from crne_liste where korisnik=$k;";
            $rezultat = $baza->select_upit($upit);
            while ($red = mysqli_fetch_array($rezultat)) {
                $upit = "select naziv from menze where id=" . $red["menza"] . ";";
                $rezultat2 = $baza->select_upit($upit);
                $r = mysqli_fetch_array($rezultat2);
                if (datum()<$red[3])
                {echo "Menza:$r[0]<br>Kazna vrijedi do:$red[3]<br><hr>";}
            }
            ?>
            Vaše rezervacije:<br>
            Odaberi menzu:<select name="menze" onchange="javascript:dajPodatke(this)">
                <option value="0"></option>
                <?php
                $upit = "select * from menze";
                $rezultat = $baza->select_upit($upit);
                while ($red = mysqli_fetch_array($rezultat)) {
                    echo '<option value=' . $red["id"] . '>' . $red["naziv"] . '</option>';
                }
                ?>
            </select>
            <input type="date" name="datum" id="datum" value="<?php echo datum(); ?>" style="visibility: hidden; display: none;">
            <div id="cilj"></div><hr>
        </div>
    </body>
</html>
