<?php
include_once 'baza_class.php';
include_once 'vrijeme.php';
include_once './dnevnik.php';
dnevnik_unos();
if (!empty($_POST)) {
    $korisnik = $_POST["korime"];
    $lozinka = $_POST["lozinka"];
    $plozinka = $_POST["p_lozinka"];
    $spol = $_POST["spol"];
    $ime = $_POST["ime"];
    $prezime = $_POST["prezime"];
    $dan = $_POST["dan"];
    $mjesec = $_POST["mjesec"];
    $godina = $_POST["godina"];
    $jmbag = $_POST["jmbag"];
    $drzava = $_POST["drzava"];
    $mjesto = $_POST["mjesto"];
    $ulica = $_POST["ulica"];
    $mail = $_POST["mail"];
    $kontakt = $_POST["kontakt"];
    $greska = "";
    $prekid = false;
    $baza = new Baza();
    foreach ($_POST as $key => $value) {
        if (empty($_POST[$key]) || !isset($_POST[$key])) {
            $greska .= "Svi elementi formulara moraju biti popunjeni.<br>";
            $prekid = true;
            break;
        }
    }
    if (strlen($lozinka) < 8) {
        $greska .= "Lozinka mora imati najmanje 8 znakova.<br>";
        $prekid = true;
    }
    if (strcmp($lozinka, $plozinka) !== 0) {
        $greska .= "Lozinka i ponovljena lozinka nisu jednake.<br>";
        $prekid = true;
    }
    if (!isset($_POST["g-recaptcha-response"]) && $_POST["g-recaptcha-response"]) {
        $greska .= "Morate dokazati da niste robot<br>";
        $prekid = true;
    }
    $upit = "select mail from korisnici;";
    $rezultat = $baza->select_upit($upit);
    while ($red = mysqli_fetch_array($rezultat)){
        if($mail == $red[0]){
            $greska .= "Postoji korisnik s unesenim mailom<br>";
            $prekid = true;
        }
    }

    if ($prekid) {
        $greska .= 'Pogrešno su uneseni podaci za registraciju!';
    } else {
        $adresa = $drzava . ", " . $mjesto . ", " . $ulica;
        $lozinka = md5($lozinka);
        $upit = "insert into korisnici value (default,'$korisnik','$lozinka','$spol','$ime','$prezime','$godina-$mjesec-$dan','$jmbag','$adresa','$mail','$kontakt',default, default, default);";
        if ($baza->ostali_upiti($upit) == false) {
            echo "Doslo je do pogreske!";
            exit();
        }
        $datumIVrijeme = datum_i_vrijeme();
        $znakovi = "$datumIVrijeme.$korime";
        $aktivacijskiKod = "";
        do {
            for ($i = 0; $i < 10; $i++) {
                $aktivacijskiKod .= $znakovi[rand(0, strlen($znakovi) - 1)];
                $aktivacijskiKod = str_replace(' ', '', $aktivacijskiKod);
            }
            $upit = "select count(*) from aktivacija_korisnika where kod = '$aktivacijskiKod';";
            $rezultat = $baza->select_upit($upit);
            $red = $rezultat->fetch_array();
        } while ($red[0] != 0);
        $vrijeme = datum_i_vrijeme();
        $upit = "insert into aktivacija_korisnika value ('$aktivacijskiKod','$korisnik','$vrijeme');";
        if ($baza->ostali_upiti($upit) == false) {
            echo "Doslo je do pogreske";
            exit();
        }
        $mail_to = $mail;
        $mail_from = "From: marmihajl@foi.hr";
        $mail_subject = "Aktivacija korisnickog racuna";
        $mail_body = "Za aktivaciju korisnickog racuna posjetite link: https://barka.foi.hr/WebDiP/2015_projekti/WebDiP2015x054/aktivacija.php?akt=" . $aktivacijskiKod;
        mail($mail_to, $mail_subject, $mail_body, $mail_from);
        header("Location: prijava.php");
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
        <script src='https://www.google.com/recaptcha/api.js'></script>


    </head>
    <body>
        <script type="text/javascript" src="provjera_registracije.js"></script>
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

            <a class="greske"><?php
                global $greska;
                echo $greska;
                ?></a>
            <form id="registracija" name="registracija" onsubmit="return provjera()" method="post" action="">
                <label for="korime" class="labela">Korisničko ime:</label>
                <input type="text" id="korime" name="korime" class="unos" placeholder="Korisničko ime">
                <div id="kime"></div>
                <label for="lozinka" class="labela">Lozinka:</label>
                <input type="password" id="lozinka" name="lozinka" class="unos" placeholder="Lozinka" onblur="provjera_lozinke()">
                <div id="lozka"></div>
                <label for="p_lozinka" class="labela">Potvrda lozinke:</label>
                <input type="password" id="p_lozinka" name="p_lozinka" class="unos" placeholder="Lozinka" onblur="identicnost()">
                <div id="potvrda"></div>
                <label for="spol" class="labela">Spol:</label>
                <select id="spol" name="spol" size="1" class="unos">
                    <option></option>
                    <option value="1">M</option>
                    <option value="2">Ž</option>
                </select><br>
                <label for="ime" class="labela">Ime:</label>
                <input type="text" id="ime" name="ime" class="unos" placeholder="Ime"><br>
                <label for="prezime" class="labela">Prezime:</label>
                <input type="text" id="prezime" name="prezime" class="unos" placeholder="Prezime"><br>
                <label for="jmbag">JMBAG:</label>
                <input type="text" name="jmbag" id="jmbag" placeholder="JMBAG" onblur="jmbag_test()">
                <div id="jmbag_test"></div>
                <label for="dan" class="labela">Dan:</label>
                <input name="dan" id="dan" type="number">
                <label for="mjesec" class="labela">Mjesec:</label>
                <input list="mjesec" name="mjesec" class="unos">
                <datalist id="mjesec">
                    <option value=1>Sječanj</option>
                    <option value=2>Veljača</option>
                    <option value=3>Ožujak</option>
                    <option value=4>Travanj</option>
                    <option value=5>Svibanj</option>
                    <option value=6>Lipanj</option>
                    <option value=7>Srpanj</option>
                    <option value=8>Kolovoz</option>
                    <option value=9>Rujan</option>
                    <option value=10>Listopad</option>
                    <option value=11>Studeni</option>
                    <option value=12>Prosinac</option>
                </datalist>
                <label for="godina" class="labela">Godina:</label>
                <input type="number" name="godina" id="godina" class="unos" placeholder="Godina"><br>
                <label>Adresa:</label>
                <input type="text" id="drzava" name="drzava" placeholder="Država">
                <input type="text" id="mjesto" name="mjesto" placeholder="Grad">
                <input type="text" id="ulica" name="ulica" placeholder="Ulica i kučni broj"><br>
                <label for="mail" class="labela">E-mail:</label>
                <input type="text" id="mail" name="mail" class="unos" placeholder="ime@posluzitelj.xxx" onblur="email()">
                <div id="email_verif"></div>
                <label for="kontakt">Kontakt:</label>
                <input type="text" id="kontakt" name="kontakt" placeholder="Kontakt">
                <div id="kont"></div>
                <label for="dokaz" class="labela">Dokaži da nisi robot</label>
                <div class="g-recaptcha" data-sitekey="6LdkfR4TAAAAAHmGli8PfzFhhLcnPlPk3hWllmnF"></div><br>
                <input type="submit" value="Potvrdi unos" class="unos">
            </form>
            <script type="text/javascript" src="marmihajl.js"></script>
    </body>

</html>
