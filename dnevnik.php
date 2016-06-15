<?php
if (!isset($_SESSION)) {
    session_start();
}
include_once 'baza_class.php';
include_once 'sesija.class.php';
include_once './vrijeme.php';
function dnevnik_unos(){
$korisnik = null;
$prethodna = "";
if (isset($_SERVER['HTTP_REFERER'])) {
    $prethodna = $_SERVER['HTTP_REFERER'];
}
if(Sesija::dajKorisnika()!==null){
    $korisnik = $_SESSION[Sesija::KORISNIK]["id"];
}
$var = substr($_SERVER["REQUEST_URI"], strrpos($_SERVER["REQUEST_URI"],"/")+1);
$upit = "";
if($korisnik != null){
    $upit = "insert into dnevnik values($korisnik,'$var','".  datum()."','".vrijeme()."');";
}else{
    $upit = "insert into dnevnik values(null,'$var','".  datum()."','".vrijeme()."');";
}
$baza = new Baza();
$baza->ostali_upiti($upit);
}
function dnevni_baza($naredba,$tablica){
$korisnik = null;
$prethodna = "";
if(Sesija::dajKorisnika()!==null){
    $korisnik = $_SESSION[Sesija::KORISNIK]["id"];
}
$upit = "";
if($korisnik != null){
    $upit = "insert into dnevnik values($korisnik,'$naredba/$tablica','".  datum_i_vrijeme()."');";
}else{
    $upit = "insert into dnevnik values(null,'$naredba/$tablica','".  datum_i_vrijeme()."');";
}
$baza = new Baza();
$baza->ostali_upiti($upit);
}
?>