<?php
include_once 'baza_class.php';
include_once 'vrijeme.php';
include_once './dnevnik.php';
dnevnik_unos();
$baza = new Baza();
$uredi = $_GET["uredi"];
$id = $_GET["id"];
if ($uredi == "t") {
    $upit = "select * from korisnici where id=$id;";
    $rezultat = $baza->select_upit($upit);
    $red = mysqli_fetch_array($rezultat);
    $lozinka = $red[2];
}
if (!empty($_POST)) {
    $korisnik = $_POST["korisnicko_ime"];
    $spol = $_POST["spol"];
    $ime = $_POST["ime"];
    $prezime = $_POST["prezime"];
    $jmbag = $_POST["jmbag"];
    $mail = $_POST["mail"];
    $kontakt = $_POST["kontakt"];
    $greska = "";
    $status = $_POST["status"];
    $zakljucan = $_POST["zakljucan"];
    $tip = $_POST["tip"];
    $prekid = false;
    
    
    if(!$prekid){
        $upit = "update korisnici set id=$red[0],korisnicko_ime = '$korisnik', lozinka='$lozinka', spol='$spol', ime='$ime', prezime='$prezime',rodendan='$red[6]',jmbag='$jmbag',adresa='$red[8]',mail='$mail',kontakt='$kontakt',status='$status',zakljucan=$zakljucan,tip_korisnika=$tip where id=$id;";
        $baza->ostali_upiti($upit);
        header("Location:postavke.php");
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
                    <?php if ($uredi == "t"): ?>
                        <tr>
                            <td><label for="korisnicko_ime">Korisničko ime:</label></td>
                            <td><input type="text" id="korisnicko_ime" name="korisnicko_ime" placeholder="Korisničko ime" onchange="provjera_korisnika()" value="<?php if($uredi="t"){echo $red[1];} ?>"></td>
                            <td id="korisnik_greska"></td>
                        </tr>
                        <tr>
                            <td><label for="spol">Spol:</label></td>
                            <td><select id="spol" name="spol">
                                    <option></option>
                                    <option value="musko" <?php
                                    if ($red[3] == "musko") {
                                        echo 'selected="selected"';
                                    }
                                    ?>>M</option>
                                    <option value="zensko" <?php
                                    if ($red[3] == "zensko") {
                                        echo 'selected="selected"';
                                    }
                                    ?>>Ž</option>
                                </select></td>
                        </tr>
                        <tr>
                            <td><label for="ime">Ime:</label></td>
                            <td><input type="text" id="ime" name="ime" placeholder="Ime" value="<?php echo $red[4]; ?>"></td>
                            <td id="ime_greske"></td>
                        </tr>
                        <tr>
                            <td><label for="prezime">Prezime:</label></td>
                            <td><input type="text" id="prezime" name="prezime" placeholder="Prezime" value="<?php echo $red[5]; ?>"></td>
                        </tr>
                        <tr>
                            <td><label for="jmbag">JMBAG:</label></td>
                            <td><input type="text" id="jmbag" name="jmbag" placeholder="JMBAG" size="12" value="<?php echo $red[7]; ?>"></td>
                        </tr>
                        <tr>
                            <td><label for="mail">E-mail:</label></td>
                            <td><input type="text" id="mail" name="mail" placeholder="e-mail" value="<?php echo $red[9]; ?>"></td>
                        </tr>

                        <tr>
                            <td><label for="kontakt">Kontakt:</label></td>
                            <td><input type="text" id="kontakt" name="kontakt" placeholder="Kontakt" value="<?php echo $red[10]; ?>"></td>
                        </tr>

                        <tr>
                            <td>Status:</td><td><select id="status" name="status">
                                    <option value="aktivan" <?php if ($red[11] == "aktivan") {
                                        echo 'selected="selected"';
                                    } ?>>Aktivan</option>
                                    <option value="neaktivan" <?php if ($red[11] == "neaktivan") {
                                        echo 'selected="selected"';
                                    } ?>>Neaktivan</option>
                                </select></td>
                        </tr>
                        <tr>
                            <td>Zaključan:</td><td><select id="zakljucan" name="zakljucan">
                                    <option value="0" <?php if ($red[12] == "0") {
                                        echo 'selected="selected"';
                                    } ?>>Otključan</option>
                                    <option value="1" <?php if ($red[12] == "1") {
                                        echo 'selected="selected"';
                                    } ?>>Zaključan</option>
                                </select></td>
                        </tr>
                        <tr>
                            <td>Tip korisnika:</td><td><select id="tip" name="tip">
                                    <option value="1" <?php if($red[13]=="1"){echo 'selected="selected"';} ?>>Administrator</option>
                                    <option value="2" <?php if($red[13]=="2"){echo 'selected="selected"';} ?>>Moderator</option>
                                    <option value="3" <?php if($red[13]=="3"){echo 'selected="selected"';} ?>>Korisnik</option>
                                </select></td>
                        </tr>

                        <tr><td><input type="submit" id="potvrdi" name="potvrdi" value="Potvrdi"></td></tr>

<?php endif; ?>
                </table>
            </form>
        </div>



    </body>

</html>