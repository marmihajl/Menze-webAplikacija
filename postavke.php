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
if (!empty($_FILES["menze_csv"])) {
    move_uploaded_file($_FILES["menze_csv"]["tmp_name"], "./datoteke/datoteka.csv");
    $datoteka = fopen("./datoteke/datoteka.csv", 'r');
    $podaci = array();

    while (!feof($datoteka)) {
        $upload = false;
        $redDatoteke = fgets($datoteka);
        $red = explode(";", $redDatoteke);
        $upit = "select naziv from menze";
        $rezultat = $baza->select_upit($upit);
        while ($r = mysqli_fetch_array($rezultat)) {
            if ($r[0] == $red[0]) {
                $upload = true;
                break;
            }
        }
        if ($upload) {
            $upit = "update menze set naziv = '$red[0]', adresa = '$red[1]' where naziv = '$red[0]';";
            $baza->ostali_upiti($upit);
        } else {
            $upit = "insert into menze values(null,'$red[0]','$red[1]',default,default);";
            $baza->ostali_upiti($upit);
        }
    }
    fclose($datoteka);
}
if (!empty($_FILES["korisnici_csv"])) {
    move_uploaded_file($_FILES["korisnici_csv"]["tmp_name"], "./datoteke/datoteka.csv");
    $datoteka = fopen("./datoteke/datoteka.csv", 'r');
    $podaci = array();

    while (!feof($datoteka)) {
        $upload = false;
        $redDatoteke = fgets($datoteka);
        $red = explode(";", $redDatoteke);
        $upit = "select korisnicko_ime from korisnici";
        $rezultat = $baza->select_upit($upit);
        while ($r = mysqli_fetch_array($rezultat)) {
            if ($r[0] == $red[0]) {
                $upload = true;
                break;
            }
        }
        $lozinka = md5($red[1]);
        if ($upload) {

            $upit = "update korisnici set korisnicko_ime = '$red[0]', lozinka='$lozinka', spol = '$red[2]',ime='$red[3]',prezime='$red[4]', rodendan='$red[5]', jmbag='$red[6]',adresa='$red[7]',mail='$red[8]', kontakt='$red[9]',status='$red[10]',zakljucan=$red[11],tip_korisnika=$red[12] where korisnicko_ime = '$red[0]';";
            $baza->ostali_upiti($upit);
        } else {
            $upit = "insert into korisnici values(null,'$red[0]','$lozinka','$red[2]','$red[3]','$red[4]','$red[5]','$red[6]','$red[7]','$red[8]','$red[9]','$red[10]',$red[11],$red[12])";
            $baza->ostali_upiti($upit);
        }
    }
    fclose($datoteka);
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
            <form action="spremi_pomak.php">
                <input type="submit" value="Pomakni vrijeme">
            </form>
                <table border="1" style="border-color: black;">
                    <tr><td></td><td></td><td>Pomak</td></tr>
                    <?php
                    $upit = "select * from pomak";
                    $rezultat = $baza->select_upit($upit);
                    $red = mysqli_fetch_array($rezultat);
                    $red = $red[0];
                    ?>
                    <tr><td><a href="dodaj_pomak.php?uredi=t">Uredi</a></td><td><a href="izbrisi.php?stranica=pomak">Izbriši</a></td><td><?php echo $red;?></td></tr>
                    <tr><td><a href="dodaj_pomak.php?uredi=f">Dodaj pomak</a><td></tr>
                </table>
                <hr>
            MENZE
            <table border="1" style="border-color: black;">
                <tr>
                    <td></td><td></td><td>ID</td><td>Naziv</td><td>Adresa</td><td>Sviđa</td><td>Ne sviđa</td>
                </tr>
                <?php
                $upit = "select * from menze;";
                $rezultat = $baza->select_upit($upit);
                ?>

                <?php while ($red = mysqli_fetch_array($rezultat)): ?>
                    <tr>
                        <td><a href="<?php echo "dodaj_menzu.php?uredi=t&id=" . $red[0]; ?>">Uredi</a></td><td><a href="<?php echo "izbrisi.php?stranica=menze&id=" . $red[0]; ?>">Izbriši</a></td><td><?php echo $red[0]; ?></td><td><?php echo $red[1]; ?></td><td><?php echo $red[2]; ?></td><td><?php echo $red[3]; ?></td><td><?php echo $red[4]; ?></td>
                    </tr>
                <?php endwhile; ?>
                <tr>
                    <td><a href="dodaj_menzu.php?uredi=f">Dodaj novu menzu</a></td>
                </tr>
            </table>
            <form action="postavke.php" method="post" id="datoteka_menza" name="datoteka_menza" enctype="multipart/form-data">
                <div>
                    <input type="file" name="menze_csv" id="menze_csv" />
                </div>

                <div>
                    <input type="submit" value="Upload" />
                </div>
            </form>
            <hr>
            MODERATORI
            <table border="1" style="border-color: black;">
                <tr>
                    <td></td><td></td><td>Ime i prezime</td><td>Menza</td>
                </tr>
                <?php
                $upit = "select * from moderatori;";
                $rezultat = $baza->select_upit($upit);
                ?>

                <?php while ($red = mysqli_fetch_array($rezultat)): ?>
                    <?php
                    $upit = "select ime,prezime from korisnici where id = $red[0];";
                    $i = $baza->select_upit($upit);
                    $im = mysqli_fetch_array($i);
                    $ime = $im[0];
                    $prezime = $im[1];
                    $upit = "select naziv from menze where id=$red[1];";
                    $m = $baza->select_upit($upit);
                    $menza = mysqli_fetch_array($m);
                    $menza = $menza[0];
                    ?>
                    <tr>
                        <td><a href="<?php echo "dodaj_moderatora.php?uredi=t&k=" . $red[0] . "&m=" . $red[1]; ?>">Uredi</a></td><td><a href="<?php echo "izbrisi.php?stranica=moderatori&k=" . $red[0] . "&m=" . $red[1]; ?>">Izbriši</a></td><td><?php echo $ime . " " . $prezime; ?></td><td><?php echo $menza; ?></td>
                    </tr>
                <?php endwhile; ?>
                <tr>
                    <td><a href="dodaj_moderatora.php?uredi=f">Dodaj novog moderatora</a></td>
                </tr>
            </table><hr>
            TIPOVI KORISNIKA
            <table border="1" style="border-color: black;">
                <tr>
                    <td></td><td></td><td>ID</td><td>Tip korisnika</td><td>Opis</td>
                </tr>
                <?php
                $upit = "select * from tipovi_korisnika;";
                $rezultat = $baza->select_upit($upit);
                ?>

                <?php while ($red = mysqli_fetch_array($rezultat)): ?>
                    <tr>
                        <td><a href="<?php echo 'dodaj_tip_korisnika.php?uredi=t&id=' . $red[0]; ?>">Uredi</a></td><td><a href="<?php echo "izbrisi.php?stranica=tipovi_korisnika&id=" . $red[0]; ?>">Izbriši</a></td><td><?php echo $red[0]; ?></td><td><?php echo $red[1]; ?></td><td><?php echo $red[2]; ?></td>
                    </tr>
                <?php endwhile; ?>
                <tr>
                    <td><a href="<?php echo 'dodaj_tip_korisnika.php?uredi=f'; ?>">Dodaj novi tip korisnika</a></td>
                </tr>
            </table><hr>
            KORISNICI
            <table border="1" style="border-color: black;">
                <tr>
                    <td></td><td></td><td>ID</td><td>Korisničko ime</td><td>Lozinka</td><td>Spol</td><td>Ime</td><td>Prezime</td><td>Rođendan</td><td>JMBAG</td><td>Adresa</td><td>E-mail</td><td>Kontakt</td><td>Status</td><td>Zakljucan</td><td>Tip korisnika</td>
                </tr>
                <?php
                $upit = "select * from korisnici;";
                $rezultat = $baza->select_upit($upit);
                ?>

                <?php while ($red = mysqli_fetch_array($rezultat)): ?>
                    <tr>
                        <td><a href="<?php echo "dodaj_korisnika.php?uredi=t&id=" . $red[0]; ?>">Uredi</a></td><td>Izbriši</td><td><?php echo $red[0]; ?></td><td><?php echo $red[1]; ?></td><td><?php echo $red[2]; ?></td><td><?php echo $red[3]; ?></td><td><?php echo $red[4]; ?></td><td><?php echo $red[5]; ?></td><td><?php echo $red[6]; ?></td><td><?php echo $red[7]; ?></td><td><?php echo $red[8]; ?></td><td><?php echo $red[9]; ?></td><td><?php echo $red[10]; ?></td><td><?php echo $red[11]; ?></td><td><?php
                            if ($red[12] == 0) {
                                echo "otključan";
                            } else {
                                echo "zaključan";
                            }
                            ?></td><td><?php echo $red[13]; ?></td>
                    </tr>
                <?php endwhile; ?>
                <tr>
                    <td>Dodaj novog korisnika</td>
                </tr>
            </table>
            
            <form action="postavke.php" method="post" name="datoteka_korisnici" id="datoteka_korisnici" enctype="multipart/form-data">
                <div>
                    <input type="file" name="korisnici_csv" id="korisnici_csv" />
                </div>

                <div>
                    <input type="submit" value="Upload" />
                </div>
            </form><hr>
            AKTIVACIJSKI KODOVI
            <table border="1" style="border-color: black;">
                <tr>
                    <td></td><td></td><td>Kod</td><td>Korisnik</td><td>Datum i vrijeme</td>
                </tr>
                <?php
                $upit = "select * from aktivacija_korisnika;";
                $rezultat = $baza->select_upit($upit);
                ?>

                <?php while ($red = mysqli_fetch_array($rezultat)):?>
                    <tr>
                        <td><a href="<?php echo "dodaj_aktivacijski_kod.php?uredi=t&kod=".$red[0]; ?>">Uredi</a></td><td><a href="<?php echo "izbrisi.php?stranica=aktivacija_korisnika&id=".$red[0]; ?>">Izbriši</a></td><td><?php echo $red[0]; ?></td><td><?php echo $red[1]; ?></td><td><?php echo $red[2]; ?></td>
                    </tr>
                <?php endwhile; ?>
                <tr>
                    <td><a href="<?php echo "dodaj_aktivacijski_kod.php?uredi=f"; ?>">Dodaj novu aktivaciju korisnika</a></td>
                </tr>
            </table>
            <hr>
            CRNE LISTE
            <table border="1" style="border-color: black;">
                <tr>
                    <td></td><td></td><td>Menza</td><td>Korisnik</td><td>Datum početka</td><td>Datum završetka</td>
                </tr>
                <?php
                $upit = "select * from crne_liste;";
                $rezultat = $baza->select_upit($upit);
                ?>

                <?php while ($red = mysqli_fetch_array($rezultat)):?>
                <?php
                $upit = "select naziv from menze where id=$red[0];";
                $rez = $baza->select_upit($upit);
                $menza = mysqli_fetch_array($rez);
                $menza = $menza[0];
                $upit = "select ime,prezime from korisnici where id=$red[1];";
                $rez = $baza->select_upit($upit);
                $korisnik = mysqli_fetch_array($rez);
                $korisnik = $korisnik[0]." ".$korisnik[1];
                ?>
                    <tr>
                        <td><a href="<?php echo "dodaj_crnu_listu.php?uredi=t&m=".$red[0]."&k=".$red[1]."&d=".$red[2]; ?>">Uredi</a></td><td><a href="<?php echo "izbrisi.php?stranica=crne_liste&m=".$red[0]."&k=".$red[1]."&d=".$red[2]; ?>">Izbriši</a></td><td><?php echo $menza; ?></td><td><?php echo $korisnik; ?></td><td><?php echo $red[2]; ?></td><td><?php echo $red[3]; ?></td>
                    </tr>
                <?php endwhile; ?>
                <tr>
                    <td><a href="<?php echo "dodaj_crnu_listu.php?uredi=f"; ?>">Dodaj korisnika na crnu listu</a></td>
                </tr>
            </table>
            <hr>
            LAJKOVI MENIJA
            <table border="1" style="border-color: black;">
                <tr>
                    <td></td><td></td><td>Meni</td><td>Korisnik</td><td>Datum</td>
                </tr>
                <?php
                $upit = "select * from lajkovi;";
                $rezultat = $baza->select_upit($upit);
                ?>

                <?php while ($red = mysqli_fetch_array($rezultat)):?>
                <?php
                $upit = "select naziv from meni where id=$red[0];";
                $rez = $baza->select_upit($upit);
                $menza = mysqli_fetch_array($rez);
                $menza = $menza[0];
                $upit = "select ime,prezime from korisnici where id=$red[1];";
                $rez = $baza->select_upit($upit);
                $korisnik = mysqli_fetch_array($rez);
                $korisnik = $korisnik[0]." ".$korisnik[1];
                ?>
                    <tr>
                        <td><a href="<?php echo "dodaj_lajk.php?uredi=t&m=".$red[0]."&k=".$red[1]."&d=".$red[2]; ?>">Uredi</a></td><td><a href="<?php echo "izbrisi.php?stranica=lajkovi&m=".$red[0]."&k=".$red[1]."&d=".$red[2]; ?>">Izbriši</a></td><td><?php echo $menza; ?></td><td><?php echo $korisnik; ?></td><td><?php echo $red[2]; ?></td>
                    </tr>
                <?php endwhile; ?>
                <tr>
                    <td><a href="<?php echo "dodaj_lajk.php?uredi=f"; ?>">Dodaj lajk meniju</a></td>
                </tr>
            </table>
            <hr>
            LAJKOVI MENZA
            <table border="1" style="border-color: black;">
                <tr>
                    <td></td><td></td><td>Meni</td><td>Korisnik</td><td>Datum</td>
                </tr>
                <?php
                $upit = "select * from lajkovi_menza;";
                $rezultat = $baza->select_upit($upit);
                ?>

                <?php while ($red = mysqli_fetch_array($rezultat)):?>
                <?php
                $upit = "select naziv from menze where id=$red[0];";
                $rez = $baza->select_upit($upit);
                $menza = mysqli_fetch_array($rez);
                $menza = $menza[0];
                $upit = "select ime,prezime from korisnici where id=$red[1];";
                $rez = $baza->select_upit($upit);
                $korisnik = mysqli_fetch_array($rez);
                $korisnik = $korisnik[0]." ".$korisnik[1];
                ?>
                    <tr>
                        <td><a href="<?php echo "dodaj_lajk_menze.php?uredi=t&m=".$red[0]."&k=".$red[1]."&d=".$red[2]; ?>">Uredi</a></td><td><a href="<?php echo "izbrisi.php?stranica=lajkovi_menze&m=".$red[0]."&k=".$red[1]."&d=".$red[2]; ?>">Izbriši</a></td><td><?php echo $menza; ?></td><td><?php echo $korisnik; ?></td><td><?php echo $red[2]; ?></td>
                    </tr>
                <?php endwhile; ?>
                <tr>
                    <td><a href="<?php echo "dodaj_lajk_menze.php?uredi=f"; ?>">Dodaj lajk meniju</a></td>
                </tr>
            </table>
            <hr>
            SLIKE
            <table border="1" style="border-color: black;">
                <tr>
                    <td></td><td></td><td>Korisnik</td><td>Slika</td><td>Tagovi</td>
                </tr>
                <?php
                $upit = "select * from slike;";
                $rezultat = $baza->select_upit($upit);
                ?>

                <?php while ($red = mysqli_fetch_array($rezultat)):?>
                <?php
                $upit = "select ime,prezime from korisnici where id=$red[0];";
                $rez = $baza->select_upit($upit);
                $korisnik = mysqli_fetch_array($rez);
                $korisnik = $korisnik[0]." ".$korisnik[1];
                ?>
                    <tr>
                        <td><a href="<?php echo "dodaj_sliku.php?uredi=t&k=".$red[0]."&s=".$red[1]; ?>">Uredi</a></td><td><a href="<?php echo "izbrisi.php?stranica=slike&k=".$red[0]."&s=".$red[1]; ?>">Izbriši</a></td><td><?php echo $korisnik; ?></td><td><?php echo $red[1]; ?></td><td><?php echo $red[2]; ?></td>
                    </tr>
                <?php endwhile; ?>
                <tr>
                    <td><a href="<?php echo "dodaj_sliku.php?uredi=f"; ?>">Dodaj sliku</a></td>
                </tr>
            </table>
            <hr>
        </div>
    </body>
</html>
