<?php
include_once './baza_class.php';
$baza = new Baza();
$uredi = $_GET["uredi"];
if($uredi == "t"){
    $menza = $_GET["m"];
    $korisnik = $_GET["k"];
    $datum = $_GET["d"];
    $upit = "select * from crne_liste where menza=$menza and korisnik=$korisnik and datum_pocetka = '$datum';";
    $rezultat = $baza->select_upit($upit);
    $red = mysqli_fetch_array($rezultat);
}
if(!empty($_POST)&&$uredi=="t"){
    $menza2 = $_POST["menza"];
    $korisnik2 = $_POST["korisnik"];
    $pocetak2 = $_POST["pocetak"];
    $kraj = $_POST["kraj"];
    $upit = "update crne_liste set menza=$menza2, korisnik=$korisnik2, datum_pocetka='$pocetak2', datum_zavrsetka='$kraj' where menza=$menza and korisnik=$korisnik and datum_pocetka = '$datum';";
    $baza->ostali_upiti($upit);
    header("Location:postavke.php");
}
elseif(!empty($_POST)&&$uredi=="f"){
    $menza2 = $_POST["menza"];
    $korisnik2 = $_POST["korisnik"];
    $pocetak2 = $_POST["pocetak"];
    $kraj = $_POST["kraj"];
    $upit = "insert into crne_liste values ($menza2,$korisnik2,'$pocetak2','$kraj');";
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
            <form name="registracija" id="registracija" method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
                <table>
                                <tr><td>Menza:</td><td><select id="menza" name="menza">
                                            <option></option>
                                            <?php $upit = "select * from menze;";
                                            $rez = $baza->select_upit($upit);
                                            while($r = mysqli_fetch_array($rez)){?>
                                            <option value="<?php echo $r[0];?>"<?php if($uredi=="t"&&$r[0]==$red[0]){echo 'selected="selected"';} ?>><?php echo $r[1];?></option>
                                            <?php } ?>
                            </select></td></tr>
                                            <tr><td>Korisnik:</td><td><select id="korisnik" name="korisnik">
                                                        <option></option>
                                                        <?php $upit = "select id,ime,prezime from korisnici;";
                                                        $rez = $baza->select_upit($upit);
                                                        while ($r = mysqli_fetch_array($rez)){?>
                                                        <option value="<?php echo $r[0];?>" <?php if($uredi=="t"&&$r[0]==$red[1]){echo 'selected="selected"';} ?>><?php echo $r[1]." ".$r[2];?></option>
                                                        <?php }?>
                                        </select></td></tr>
                    <tr><td>Datum početka:</td><td><input type="date" name="pocetak" id="pocetak" value="<?php if($uredi == "t"){ echo $red[2]; } ?>"></td></tr>
                    <tr><td>Datum završetka:</td><td><input type="date" name="kraj" id="kraj" value="<?php if($uredi == "t"){ echo $red[3]; } ?>"></td></tr>
                    <tr><td><input type="submit" value="Potvrdi"></tr>
                </table>
            </form>

        </div>



    </body>

</html>