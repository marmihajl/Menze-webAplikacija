<?php
include_once 'baza_class.php';
$baza = new Baza();
$output = '<?xml version="1.0" encoding="utf-8" standalone="yes"?>' . "\n";
$output .= '<meni>';

$query = "select id,naziv,opis,datum,pocetno_stanje,trenutno_stanje, svida, nesvida, menza from meni order by menza,(pocetno_stanje-trenutno_stanje) desc;";

$rezultat = $baza->select_upit($query);
while ($red = mysqli_fetch_array($rezultat)){
    $output .= '<name id="'.$red[0].'" naziv="'.$red[1].'" opis="'.$red[2].'" datum="'.$red[3].'" pocetno="'.$red[4].'" trenutno="'.$red[5].'" svida="'.$red[6].'" nesvida="'.$red[7].'" menza="'.$red[8].'"></name>';
}
$output .= '</meni>';
header("Content-Type: text/xml");
print $output;
?>