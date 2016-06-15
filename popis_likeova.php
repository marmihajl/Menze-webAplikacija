<?php
include_once 'baza_class.php';
$baza = new Baza();
include_once 'sesija.class.php';
session_start();
$output = '<?xml version="1.0" encoding="utf-8" standalone="yes"?>' . "\n";
$output .= '<like>';
$id = $_SESSION[Sesija::KORISNIK]["id"];

$query = "select * from meni;";
$rezultat = $baza->select_upit($query);
while ($red = mysqli_fetch_array($rezultat)){
    $output .= '<name menza="'.$red[0].'" msvida="'.$red[1].'" mnesvida="'.$red[2].'" meni="'.$red[3].'" nsvida="'.$red[4].'" nnesvida="'.$red[5].'"></name>';
}
$output .= '</like>';
header("Content-Type: text/xml");
print $output;
?>