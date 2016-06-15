<?php

if (!isset($_SESSION)) {
    session_start();
}
include_once 'baza_class.php';
$baza = new Baza();
include_once './dnevnik.php';
dnevnik_unos();
function vri() {
    global $baza;
    $upit = "select * from pomak";
    $rezultat = $baza->select_upit($upit);
    $red = $rezultat->fetch_array();
    $vrijeme = time() + ($red[0] * 60 * 60);
    return $vrijeme;
}

function datum() {
    return date("Y-m-d", vri());
}

function vrijeme() {
    return date("H:i:s", vri());
}

function datum_i_vrijeme() {
    return date("Y-m-d H:i:s", vri());
}
?>