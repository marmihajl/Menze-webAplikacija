<?php
include_once 'baza_class.php';
include_once 'sesija.class.php';
session_start();
$baza = new Baza();
$output = '<?xml version="1.0" encoding="utf-8" standalone="yes"?>' . "\n";
$output .= '<rezervacije>';
$id = $_SESSION[Sesija::KORISNIK]["id"];
$query = "select meni, korisnik, kolicina, vrijeme, status, datum from rezervacije where korisnik=$id order by korisnik;";

$rezultat = $baza->select_upit($query);
while ($red = mysqli_fetch_array($rezultat)){
    $query2 ="select naziv from meni where id=$red[0]";
    $rez = $baza->select_upit($query2);
    $meni = mysqli_fetch_array($rez);
    $query3 ="select menza from meni where id=$red[0]";
    $rezz = $baza->select_upit($query3);
    $menza = mysqli_fetch_array($rezz);
    $output .= '<name meni="'.$meni[0].'" korisnik="'.$red[1].'" menza="'.$menza[0].'" kolicina="'.$red[2].'" datum="'.$red[5].'" vrijeme="'.$red[3].'" status="'. $red[4].'"></name>';
}
$output .= '</rezervacije>';
header("Content-Type: text/xml");
print $output;
?>