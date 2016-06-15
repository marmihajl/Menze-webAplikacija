<?php
if ($_SERVER["HTTPS"] != "on") {
    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
    exit();
}
include_once 'sesija.class.php';
include_once 'baza_class.php';
include_once './vrijeme.php';
$baza = new Baza();
if (!isset($_SESSION)) {
    session_start();
}
include_once './dnevnik.php';
dnevnik_unos();
if (!empty($_POST)) {
    $k = $_POST["korisnik"];
    $m = $_POST["meni"];
    $meni = $_POST["menza"];
    $status = $_POST["status"];
    $upit_post = "update rezervacije set status = '$status' where korisnik = $k and meni = $meni;";
    $baza->ostali_upiti($upit_post);
    $upit_post = "select mail from korisnici where id = $k";
    $r = $baza->select_upit($upit_post);
    $mail = mysqli_fetch_array($r);
    $dolazak = "";
    if (isset($_POST["dolazak"])) {
        $dolazak = $_POST["dolazak"];
        $kraj = vri() + 24*60*60*7;
        $kraj = date("Y-m-d",$kraj);
        if ($dolazak == 1) {
            $upit = "insert into crne_liste values ($m,$k,'".datum()."','$kraj');";
            $baza->ostali_upiti($upit);
            $upit = "update rezervacije set prikazi = 0 where korisnik=$k and meni = $meni;";
            $baza->ostali_upiti($upit);
        }
        else{
            $upit = "update rezervacije set prikazi = 0 where korisnik=$k and meni = $meni;";
            $baza->ostali_upiti($upit);
        }
    }
    $upit = "select prikazi from rezervacije where korisnik = $k and meni = $meni;";
    $rez = $baza->select_upit($upit);
    $r = mysqli_fetch_array($rez);
    $r = $r[0];
    if($status == "prihvačeno" && $r == 1){
      $mail_to = $mail[0];
      $mail_from = "From: marmihajl@foi.hr";
      $mail_subject = "Rezervacija";
      $mail_body = "Rezervacija u menzi vam je prihvačena";
      mail($mail_to, $mail_subject, $mail_body, $mail_from);
      }
      if($status == "odbijeno" && $r == 1){
      $mail_to = $mail[0];
      $mail_from = "From: marmihajl@foi.hr";
      $mail_subject = "Rezervacija";
      $mail_body = "Rezervacija u menzi vam je odbijena";
      mail($mail_to, $mail_subject, $mail_body, $mail_from);
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
            <?php if (isset($_SESSION[Sesija::KORISNIK])): ?>
                <div class="zaglavlje"><a><?php echo $_SESSION[Sesija::KORISNIK]["korisnicko_ime"]; ?></a><span>   </span><a href="odjava.php">Odjava</a></div>
            <?php endif; ?>


            <?php
            $upit = "select * from rezervacije;";
            $rezultat = $baza->select_upit($upit);
            while ($red = mysqli_fetch_array($rezultat)) {
                $upit = "select menza from meni where id = $red[0];";
                $rez = $baza->select_upit($upit);
                $menza = mysqli_fetch_array($rez);
                $korisnik = $_SESSION[Sesija::KORISNIK]["id"];
                $upit = "select count(*) from moderatori where korisnik = $korisnik and menza = $menza[0];";
                $rez = $baza->select_upit($upit);
                $potvrda = mysqli_fetch_array($rez);
                if ($potvrda[0] == 1 && ($red[5] == "prihvačeno" || $red[5] == "na čekanju") && $red[6] == 1) {
                    $upit = "select (select naziv from meni where id = $red[0]), (select jmbag from korisnici where id = $red[1]), kolicina, vrijeme, status, prikazi from rezervacije where meni=$red[0] and korisnik=$red[1];";
                    $rez = $baza->select_upit($upit);
                    $r = mysqli_fetch_array($rez);
                    $upit = "select naziv from menze where id=$menza[0]";
                    $x = $baza->select_upit($upit);
                    $y= mysqli_fetch_array($x);
                    ?>
                    <form method="post" action="pregled_rezervacija.php">
                        <table>
                            <tr style="visibility: hidden; display: none;"><td><input  type="number" id="korisni" name="korisnik" value="<?php echo $red[1]; ?>"></td>
                                <td><input type="number" id="meni" name="meni" value="<?php echo $menza[0]; ?>"></td>
                            <td><input type="number" id="menza" name="menza" value="<?php echo $red[0]; ?>"></td></tr>
                            <tr><td>Menza:</td><td><?php echo $y[0]; ?></td></tr>
                            <tr><td>Meni:</td><td><?php echo $r[0]; ?></td></tr>
                            <tr><td>Korisnik:</td><td><?php echo $r[1]; ?></td></tr>
                            <tr><td>Datum i vrijeme:</td><td><?php echo $r[3]; ?></td></tr>
                            <tr><td>Količina:</td><td><?php echo $r[2]; ?></td></tr>
                            <tr><td>Status:</td><td><select id="status" name="status">
                                        <option value="na čekanju" <?php
                                        if ($r[4] == "na čekanju") {
                                            echo 'selected = "selected"';
                                        }
                                        ?>>Na čekanju</option>
                                        <option value="prihvačeno" <?php
                                        if ($r[4] == "prihvačeno") {
                                            echo 'selected = "selected"';
                                        }
                                        ?>>Prihvačeno</option>
                                        <option value="odbijeno" <?php
                                        if ($r[4] == "odbijeno") {
                                            echo 'selected = "selected"';
                                        }
                                        ?>>Odbijeno</option>
                                    </select></td></tr>
                            <?php if ($red[5] == "prihvačeno"): ?>
                                <tr><td>Da li se korisnik pojavio?</td><td><select id="dolazak" name="dolazak">
                                            <option></option>
                                            <option value="0">Pojavio se</option>
                                            <option value="1">Nije se pojavio</option>
                                        </select></td></tr>
                            <?php endif; ?>
                            <tr><td></td><td><input type="submit" value="Potvrdi"></td></tr>

                        </table>
                    </form>
                    <?php
                }
            }
            ?>
        </div>
    </body>
</html>