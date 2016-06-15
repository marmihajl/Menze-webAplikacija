<?php
if (!isset($_SESSION)) {
    session_start();
}
include_once './sesija.class.php';
Sesija::obrisiSesiju();
include_once './dnevnik.php';
dnevnik_unos();

header("Location: prijava.php");
?>
