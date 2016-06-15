<?php
include_once './baza_class.php';
$baza = new Baza();
include_once './dnevnik.php';
dnevnik_unos();
if (!empty($_POST)) {
    $mail = $_POST["mail"];
    $upit = "select id from korisnici where mail = '$mail';";
    $rezultat = $baza->select_upit($upit);
    $red = $rezultat->fetch_array();
    $mail_to = $mail;
    $mail_from = "From: marmihajl@foi.hr";
    $mail_subject = "Promjena lozinke";
    $mail_body = "Za promjenu lozinke posjetite link: https://barka.foi.hr/WebDiP/2015_projekti/WebDiP2015x054/promjena_lozinke.php?id=" . $red[0];
    mail($mail_to, $mail_subject, $mail_body, $mail_from);
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
        <script type="text/javascript" src="provjera_registracije.js"></script>
    </head>
    <body>
        <header>
            <div class="naslov">MENZE</div>
        </header>

        <nav><ul>
                <li><a href="index.php">Početna</a></li>
                <li>Registracija</li>
                <li>Prijava</li>
                <li>Odjava</li>
            </ul></nav>
        <div class="sadrzaj">
            <div id="tekst"><form id="promjena" name="promjena" method="post" action="zahtjev_lozinke.php">
                PROMJENA LOZINKE<br>
                E-mail:
                <input type="text" id="mail" name="mail" placeholder="E-mail"><br>

                <input type="submit" id="potvrda" name="potvrda" value="Pošalji mail"><br>

                </form></div>
        </div>
    </body>
</html>