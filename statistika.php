<?php

include_once 'sesija.class.php';
include_once './baza_class.php';
include_once './dnevnik.php';
dnevnik_unos();
$baza = new Baza();
if (!isset($_SESSION)) {
    session_start();
}

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
            </ul></nav>
        <div class="sadrzaj">
            <?php if (Sesija::dajKorisnika() === null): ?>
                <div class="zaglavlje"><a href="prijava.php">Prijava</a><span>   </span><a href="registracija.php">Registracija</a></div>
            <?php endif; ?>
            <?php if (isset($_SESSION[Sesija::KORISNIK])): ?>
                <div class="zaglavlje"><a><?php echo $_SESSION[Sesija::KORISNIK]["korisnicko_ime"]; ?></a><span>   </span><a href="odjava.php">Odjava</a></div>
            <?php endif; ?>
                <table>
                    <tr>
                        <td>Menza</td><td>Meni - datum</td><td>Sviđa mi se</td><td>Ne sviđa mi se</td>
                    </tr>
                    <?php
                    $upit= "select id, naziv, svida, nesvida from menze;";
                    $rezultat = $baza->select_upit($upit);
                    while ($red = mysqli_fetch_array($rezultat)){
                        $upit = "select * from moderatori where menza = $red[0]";
                        $rez = $baza->select_upit($upit);
                        while ($r = mysqli_fetch_array($rez)){
                         if($r[0] == $_SESSION[Sesija::KORISNIK]["id"]){   
                        
?>
                    <tr>
                        <td><?php echo $red["naziv"];?></td><td></td><td><?php echo $red["svida"];?></td><td><?php echo $red["nesvida"];?></td>
                    </tr>
                        <?php
                        $upit = "select naziv,svida, nesvida, datum from meni where menza=".$red["id"].";";
                        $rez = $baza->select_upit($upit);
                        while($r = mysqli_fetch_array($rez)){?>
                    <tr>
                        <td></td><td><?php echo $r["naziv"]." - ".$r["datum"];?></td><td><?php echo $r["svida"];?></td></td><td><?php echo $r["nesvida"]?></td>
                    </tr>
                       <?php }
                    }}}
                    ?>
                </table>
        </div>
    </body>
</html>