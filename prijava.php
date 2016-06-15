<?php
include_once './dnevnik.php';
dnevnik_unos();
if ($_SERVER["HTTPS"] != "on") {
    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
    exit();
}
include_once 'baza_class.php';
include_once 'sesija.class.php';
include_once 'korisnici.class.php';


$baza = new Baza();
$korisnik2 = new Korisnik();
if (!empty($_POST)) {
    $korisnik = $_POST["korisnicko_ime"];
    $lozinka = md5($_POST["lozinka"]);
    if (isset($_POST["upamti"])) {
        $upamti = $_POST["upamti"];
    }
    $upit = "select * from korisnici;";
    $rezultat = $baza->select_upit($upit);
    $korisnicka_lozinka = "";
    $pogreska = "";
    $zakljucan = 0;
    $id = 0;
    $red = "";
    $postoji = false;
    while ($red = mysqli_fetch_array($rezultat)) {

        if ($red["korisnicko_ime"] == $korisnik) {
            $korisnicka_lozinka = $red["lozinka"];
            $status = $red["status"];
            $zakljucan = $red["zakljucan"];
            $id = $red["id"];
            $postoji = true;
            break;
        } else {
            $postoji = false;
        }
    }
    if ($postoji) {
        if ((strcmp($korisnicka_lozinka, $lozinka) == 0) and ( strcmp($status, "aktivan") == 0) and ( $zakljucan < 3)) {
            $upit = "update korisnici set zakljucan = 0 where id = $id;";
            if ($baza->ostali_upiti($upit) == false) {
                $pogreska .= "Dogodila se pogreška u radu s bazom.<br>";
            }
            if (isset($upamti) != null) {
                $ime_kolacica = "korisnik";
                $vrijednost_kolacica = $korisnik;
                setcookie($ime_kolacica, $vrijednost_kolacica);
            } else {
                $_COOKIE["korisnik"] = "";
                unset($_COOKIE["korisnik"]);
            }
            Sesija::kreirajKorisnika($korisnik2->dodavanjeKorisnika($red["id"], $red["korisnicko_ime"], $red["tip_korisnika"], $red["jmbag"], $red["mail"]));
            header("Location: index.php");
        } elseif (strcmp($korisnicka_lozinka, $lozinka) != 0) {
            $pogreska .= "Unjeli ste pogresnu lozinku.<br>";
            $zakljucan++;
            $upit = "update korisnici set zakljucan = $zakljucan where id = $id;";
            if ($baza->ostali_upiti($upit) == false) {
                $pogreska .= "Dogodila se pogreška u radu s bazom.<br>";
            }
 else {
     
 }
        } elseif (strcmp($status, "neaktivan") == 0) {
            $pogreska .= "Niste aktivirali račun. Provjerite svoj e-mail.<br>";
        } elseif ($zakljucan > 2) {
            $pogreska .= "Korisnički račun vam je zaključan, kontakrirajte administratora.<br>";
        }
    } else {
        $pogreska .= "Unjeli ste nepostojeće korisničko ime.<br>";
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
            <?php
            global $pogreska;
            echo $pogreska;
            ?>
            <form id="prijava" name="prijava" method="post" action="prijava.php">
                PRIJAVA
                <table>

                    <tr>
                        <td>Korisničko ime:</td>
                        <td><input type="text" id="korisnicko_ime" name="korisnicko_ime" placeholder="Korisničko ime" value="<?php if (isset($_COOKIE["korisnik"])) echo $_COOKIE["korisnik"]; ?>"></td>
                    </tr>
                    <tr>
                        <td>Lozinka:</td>
                        <td><input type="password" id="lozinka" name="lozinka" placeholder="Lozinka"></td>
                    </tr>
                    <tr>
                        <td>
                            <label for="upamti">Zapamti moju prijavu?</label>
                        </td>
                        <td>
                            <input type="checkbox" id="upamti" name="upamti">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="submit" id="potvrda" name="potvrda">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <a href="zahtjev_lozinke.php">Zaboravio sam lozinku</a>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <a href="registracija.php">Registriraj se</a>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </body>
</html>