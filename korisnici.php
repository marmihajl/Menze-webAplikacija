<?php
include_once 'baza_class.php';
$baza = new Baza();
$output = '<?xml version="1.0" encoding="utf-8" standalone="yes"?>' . "\n";
$output .= '<korisnici>';

$query = 'SELECT korisnicko_ime ' .
        'FROM korisnici ORDER BY 1';

$rezultat = $baza->select_upit($query);
while ($red = mysqli_fetch_array($rezultat)){
    $output .= '<name kor="' . $red[0] . "\n";
}
$output .= '</korisnici>';
$baza->prekini_vezu();
header("Content-Type: text/xml");
print $output;
?>