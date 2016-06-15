<?php
include_once './baza_class.php';
$baza = new Baza();
$stranica = $_GET["stranica"];

if($stranica=="menze"){
    $id=$_GET["id"];
    $upit = "delete from menze where id=$id";
    $baza->ostali_upiti($upit);
    header("Location:postavke.php");
}
if($stranica == "moderatori"){
    $k = $_GET["k"];
    $m = $_GET["m"];
    $upit = "delete from moderatori where korisnik=$k and menza=$m;";
    $baza->ostali_upiti($upit);
    header("Location:postavke.php");
}
if($stranica=="tipovi_korisnika"){
    $id=$_GET["id"];
    $upit = "delete from tipovi_korisnika where id=$id";
    $baza->ostali_upiti($upit);
    header("Location:postavke.php");
}
if($stranica == "aktivacija_korisnika"){
    $id=$_GET["id"];
    $upit = "delete from aktivacija_korisnika where kod='$id'";
    $baza->ostali_upiti($upit);
    header("Location:postavke.php");
}
if($stranica == "crne_liste"){
    $m=$_GET["m"];
    $k=$_GET["k"];
    $d=$_GET["d"];
    $upit = "delete from crne_liste where menza=$m and korisnik=$k and datum_pocetka = '$d'";
    $baza->ostali_upiti($upit);
    header("Location:postavke.php");
}
if($stranica == "lajkovi"){
    $m=$_GET["m"];
    $k=$_GET["k"];
    $d=$_GET["d"];
    $upit = "delete from lajkovi where meni=$m and korisnik=$k and datum = '$d'";
    $baza->ostali_upiti($upit);
    header("Location:postavke.php");
}
if($stranica == "lajkovi_menze"){
    $m=$_GET["m"];
    $k=$_GET["k"];
    $d=$_GET["d"];
    $upit = "delete from lajkovi_menza where menza=$m and korisnik=$k and datum = '$d'";
    $baza->ostali_upiti($upit);
    header("Location:postavke.php");
}
if($stranica == "pomak"){
    $upit = "update pomak set pom = 0";
    $baza->ostali_upiti($upit);
    header("Location:postavke.php");
}
if($stranica == "slike"){
    $k = $_GET["k"];
    $s=$_GET["s"];
    $upit = "delete from slike where korisnik = $k and slika = '$s'";
    $baza->ostali_upiti($upit);
    header("Location:postavke.php");
}
?>