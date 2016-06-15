<?php
include_once 'sesija.class.php';
include_once './baza_class.php';
include_once './vrijeme.php';
include_once './dnevnik.php';
dnevnik_unos();
$baza = new Baza();
if (!isset($_SESSION)) {
    session_start();
}
$greska = "";
$prekid = false;
if (!empty($_POST)) {
    $naziv = $_POST["naziv"];
    $menza = $_POST["menza"];
    $datum = $_POST["datum"];
    $kolicina = $_POST["kolicina"];
    $opis = $_POST["opis"];
    foreach ($_POST as $key => $value) {
        if (empty($_POST[$key]) || !isset($_POST[$key])) {
            $greska .= "Svi elementi formulara moraju biti popunjeni.<br>";
            $prekid = true;
            break;
        }
    }
    if ($datum < datum()) {
        $greska .= "Odabrani datum nije ispravan.<br>";
        $prekid = true;
    }
    $upit = "insert into meni values(default, '$naziv','$opis', '$datum',$kolicina,$kolicina,default,default,$menza);";

    if ($prekid) {
        $greska .= "MENI NIJE DODAN! DOŠLO JE DO POGREŠKE!";
    } else {
        if (!$baza->ostali_upiti($upit)) {
            $greska .= "Došlo je do pogreške u radu s bazom.";
            $prekid = TRUE;
        }
        header("Location:menze.php");
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
                <li><a href="galerija.php">Galerija</a></li>
                <?php if($_SESSION[Sesija::KORISNIK]["tip_korisnika"]<3): ?>
                <li><a href="pregled_rezervacija.php">Pregled rezervacija</a></li>
                <?php endif;?>
                <?php if($_SESSION[Sesija::KORISNIK]["tip_korisnika"]<2): ?>
                <li><a href="postavke.php">Postavke sustava</a></li>
                <li><a href="posjecenost.php">Korištenje sustava</a></li>
                <?php endif;?>
            </ul></nav>
        <div class="sadrzaj">
            <?php echo $greska;
            ?>
            <form id="dodavanje" name="dodavanje" method="post" action="dodavanje_meni.php">
                DODAVANJE MENIJA
                <table>
                    <tr>
                        <td>Menza</td>
                        <td><select name="menza" id="menza">
                                <option></option>
                                <?php
                                include_once './baza_class.php';
                                $baza = new Baza();
                                $upit = "select * from menze";
                                $rezultat = $baza->select_upit($upit);
                                $upit = "select * from moderatori";
                                $rezultat2 = $baza->select_upit($upit);
                                while ($r = mysqli_fetch_array($rezultat2)) {
                                    $upit = "select * from menze";
                                    $rezultat = $baza->select_upit($upit);
                                    while ($red = mysqli_fetch_array($rezultat)) {

                                        if ($r["korisnik"] == $_SESSION[Sesija::KORISNIK]["id"] && $r["menza"] == $red["id"]) {
                                            echo '<option value=' . $red["id"] . '>' . $red["naziv"] . '</option>';
                                        }
                                    }
                                }
                                ?>
                            </select></td>
                    </tr> 
                    <tr>
                        <td>Naziv:</td>
                        <td><input type="text" id="naziv" name="naziv" placeholder="Naziv menija"></td>
                    </tr>
                    <tr>
                        <td>Datum:</td>
                        <td><input type="date" id="datum" name="datum"></td>
                    </tr>
                    <tr>
                        <td>Količina:</td>
                        <td><input type="number" id="kolicina" name="kolicina" placeholder="Količina"></td>
                    </tr>
                    <tr>
                        <td>Opis:</td>
                        <td><input type="textarea" id="opis" name="opis" placeholder="Opis menija"></td>
                    </tr>
                    <tr>
                        <td>
                            <input type="submit" id="potvrda" name="potvrda" value="Dodaj meni">
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </body>
</html>