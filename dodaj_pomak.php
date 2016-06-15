<?php
include_once './baza_class.php';
$baza = new Baza();
$uredi = $_GET["uredi"];
if($uredi == "t"){
    $upit = "select * from pomak;";
    $rezultat = $baza->select_upit($upit);
    $red = mysqli_fetch_array($rezultat);
}
if(!empty($_POST)){
    $pomak = $_POST["pomak"];
    $upit = "update pomak set pom = $pomak;";
    $baza->ostali_upiti($upit);
    header("Location:postavke.php");
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>MENZE-aplikacija</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="preglednik.css" rel="stylesheet" type="text/css">
        <script src='https://www.google.com/recaptcha/api.js'></script>


    </head>
    <body>
        <script type="text/javascript" src="provjera_registracije.js"></script>
        <header>
            <div class="naslov">MENZE</div>
        </header>


        <div class="sadrzaj">
            
            <a class="greske"><?php
                global $greska;
                echo $greska;
                ?></a>
            <form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
                Pomak:<input type="number" id="pomak" name="pomak" value="<?php if($uredi == "t") echo $red[0];?>">
                <input type="submit" value="Postavi pomak">
            </form>
            
        </div>
    </body>
</html>