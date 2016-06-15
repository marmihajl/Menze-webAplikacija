<?php
include_once 'baza_class.php';
$url = "http://barka.foi.hr/WebDiP/pomak_vremena/pomak.php?format=xml";
$baza = new Baza();
include_once './dnevnik.php';
dnevnik_unos();

if (!($fp = fopen($url, 'r'))) {
    echo "Problem: nije moguće otvoriti url: " . $url;
    exit;
}

$xml_string = fread($fp, 10000);
fclose($fp);

$domdoc = new DOMDocument;
$domdoc->loadXML($xml_string);

$params = $domdoc->getElementsByTagName('brojSati');
$sati = 0;
foreach ($params as $param) {
    $sati = $param->nodeValue;
}
$upit = "delete from pomak;";
$baza->ostali_upiti($upit);
$upit = "insert into pomak values ($sati);";
$baza->ostali_upiti($upit);
header("Location:postavke.php");
?>