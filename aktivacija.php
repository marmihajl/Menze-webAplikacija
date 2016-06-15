<?php
include_once './baza_class.php';
include_once './vrijeme.php';
include_once './dnevnik.php';
$baza = new Baza();
dnevnik_unos();
$aktivacijskiKod = $_GET["akt"];
$upit = "select * from aktivacija_korisnika where kod='$aktivacijskiKod';";
$rezultat = $baza->select_upit($upit);
$red = mysqli_fetch_array($rezultat);
$sada = new DateTime($red[2]);
$sustav = new DateTime(datum_i_vrijeme());
$razlika = date_diff($sada, $sustav);
if($razlika->h < 12){
    $upit = "update korisnici set status = 'aktivan' where korisnicko_ime='$red[1]';";
    $rezultat = $baza->ostali_upiti($upit);
    header("Location:prijava.php");
}
 else {
     echo "Trajanje aktivacijskog koda je isteklo. Ponovite registraciju.<br>";
     exit();
}

?>