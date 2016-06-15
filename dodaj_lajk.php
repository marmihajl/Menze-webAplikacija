<?php
include_once './baza_class.php';
$baza = new Baza();
$uredi = $_GET["uredi"];
if($uredi == "t"){
    $meni2 = $_GET["m"];
    $korisnik2 = $_GET["k"];
    $datum2 = $_GET["d"];
}
if(!empty($_POST)&&$uredi=="t"){
    $meni = $_POST["meni"];
    $korisnik = $_POST["korisnik"];
    $pocetak = $_POST["pocetak"];
    $upit = "update lajkovi set meni=$meni, korisnik=$korisnik, datum='$pocetak' where meni=$meni2 and korisnik=$korisnik2 and datum = '$datum2';";
    $baza->ostali_upiti($upit);
    header("Location:postavke.php");
}
elseif(!empty($_POST)&&$uredi=="f"){
    $meni = $_POST["meni"];
    $korisnik = $_POST["korisnik"];
    $pocetak = $_POST["pocetak"];
    $upit = "insert into lajkovi values ($meni,$korisnik,'$pocetak');";
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
                                <tr><td>Meni:</td><td><select id="meni" name="meni">
                                            <option></option>
                                            <?php $upit = "select * from meni;";
                                            $rez = $baza->select_upit($upit);
                                            while($r = mysqli_fetch_array($rez)){?>
                                            <option value="<?php echo $r[0];?>"<?php if($uredi=="t"&&$r[0]==$meni2){echo 'selected="selected"';} ?>><?php echo $r[1];?></option>
                                            <?php } ?>
                            </select></td></tr>
                                            <tr><td>Korisnik:</td><td><select id="korisnik" name="korisnik">
                                                        <option></option>
                                                        <?php $upit = "select id,ime,prezime from korisnici;";
                                                        $rez = $baza->select_upit($upit);
                                                        while ($r = mysqli_fetch_array($rez)){?>
                                                        <option value="<?php echo $r[0];?>" <?php if($uredi=="t"&&$r[0]==$korisnik2){echo 'selected="selected"';} ?>><?php echo $r[1]." ".$r[2];?></option>
                                                        <?php }?>
                                        </select></td></tr>
                    <tr><td>Datum:</td><td><input type="date" name="pocetak" id="pocetak" value="<?php if($uredi == "t"){ echo $datum2; } ?>"></td></tr>
                    <tr><td><input type="submit" value="Potvrdi"></tr>
                </table>
            </form>

        </div>



    </body>

</html>