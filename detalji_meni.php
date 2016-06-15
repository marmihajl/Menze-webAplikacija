<?php
include_once './baza_class.php';
include_once './sesija.class.php';
if (!isset($_SESSION)) {
    session_start();
}
include_once './dnevnik.php';
dnevnik_unos();
$baza = new Baza();
$id = "";
if (!empty($_GET)) {
    $id = $_GET["id"];
}
$upit = "select * from meni where id = $id;";
$rezultat = $baza->select_upit($upit);
$red = mysqli_fetch_array($rezultat);
if(!empty($_POST)){
    $kolicina = $_POST["kolicina"];
    $zbroj = $kolicina - $red["trenutno_stanje"];
    $upit2 = "update meni set trenutno_stanje = $kolicina, pocetno_stanje = pocetno_stanje + $zbroj where id=$id;"; 
    $baza->ostali_upiti($upit2);
    header("Location:menze.php");
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
                <form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
                <table>
                    <tr>
                        <td>Naziv menija: </td>
                        <td><?php echo $red["naziv"]; ?></td>
                    </tr>
                    <tr>
                        <td>Opis: </td>
                        <td><?php echo $red["opis"]; ?></td>
                    </tr>
                    <tr>
                        <td>Količina: </td>
                        <td><input type="number" name="kolicina" id="kolicina" value="<?php echo $red["trenutno_stanje"]; ?>"></td>
                    </tr>
                    <tr>
                        <td><input type="submit" value="Potvrdi promjenu"></td>
                    </tr>
                </table>
            </form>
        </div>
    </body>
</html>