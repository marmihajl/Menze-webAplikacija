<?php
include_once 'baza_class.php';
include_once 'sesija.class.php';
session_start();
include_once './dnevnik.php';
dnevnik_unos();

$baza = new Baza();
$prethodna = "javascript:history.go(-1)";
$tag = "";
if(!empty($_POST)){
    $tag = $_POST["tag"];
}
if (isset($_SERVER['HTTP_REFERER'])) {
    $prethodna = $_SERVER['HTTP_REFERER'];
}
if(file_exists($_SESSION[Sesija::KORISNIK]["korisnicko_ime"]) != 1){
    mkdir($_SESSION[Sesija::KORISNIK]["korisnicko_ime"]);
}
$target_dir = $_SESSION[Sesija::KORISNIK]["korisnicko_ime"];
$target_file = $target_dir . "/" . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
$greska = "";
if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check === false) {
        $greska .= "Datoteka nije slika.";
        $uploadOk = 0;
    }
}
if (file_exists($target_file)) {
    $greska .= "Datoteka s navedenim imenom je već uploadana.";
    $uploadOk = 0;
}
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" ) {
    $greska .= "Dozvoljeni tipovi slike su: JPG, JPEG, PNG & GIF.<br>";
    $uploadOk = 0;
}
if ($uploadOk == 0) {
    $greska .= "Slika nije uploadana.";
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        $upit = "insert into slike values (".$_SESSION[Sesija::KORISNIK]["id"].",'".$_FILES["fileToUpload"]["name"]."','$tag')";
        $baza->ostali_upiti($upit);
        header("Location:galerija.php");
        
    }
}


?>
<!DOCTYPE html>
<html>
    <head>
        <title>MENZE-aplikacija</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="preglednik.css" rel="stylesheet" type="text/css">
    </head>
    <body>

        <header>
            <div class="naslov">MENZE</div>
        </header>

        <nav><ul>
                <li><a href="index.php">Početna</a></li>
                <li><a href="menze.php">Menze</a></li>
                <?php if(Sesija::dajKorisnika() != null): ?>
                <li><a href="galerija.php">Galerija</a></li>
                <?php endif;?>
                <?php if(Sesija::dajKorisnika() != null): ?>
                <?php if($_SESSION[Sesija::KORISNIK]["tip_korisnika"]<3): ?>
                <li><a href="pregled_rezervacija.php">Pregled rezervacija</a></li>
                <?php endif;?>
                <?php endif;?>
                <?php if(Sesija::dajKorisnika() != null): ?>
                <?php if($_SESSION[Sesija::KORISNIK]["tip_korisnika"]<2): ?>
                <li><a href="postavke.php">Postavke sustava</a></li>
                <li><a href="posjecenost.php">Korištenje sustava</a></li>
                <?php endif;?>
                <?php endif;?>
            </ul></nav>
        <div class="sadrzaj">
            <?php echo "\n".$greska;?>
            <?php if ($uploadOk == 0): ?>
            <a href="<?= $prethodna ?>">Vrati se na prethodnu stranicu</a>
            <?php endif; ?>
        </div>
    </body>
</html>