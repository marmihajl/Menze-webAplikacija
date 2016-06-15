<?php
include_once 'baza_class.php';
$baza = new Baza();
include_once 'sesija.class.php';
session_start();
$output = '<?xml version="1.0" encoding="utf-8" standalone="yes"?>' . "\n";
$output .= '<slike>';
$id = $_SESSION[Sesija::KORISNIK]["id"];

$query = "select (select korisnicko_ime from korisnici where id=$id),slika,tagovi from slike where korisnik=$id;";

$rezultat = $baza->select_upit($query);
while ($red = mysqli_fetch_array($rezultat)){
    $output .= '<name korisnik="'.$red[0].'" naziv="'.$red[1].'" tag="'.$red[2].'"></name>';
}
$output .= '</slike>';
header("Content-Type: text/xml");
print $output;
?>