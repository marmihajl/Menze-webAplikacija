<?php
include_once './baza_class.php';
include_once './dnevnik.php';
include_once './dnevnik.php';
dnevnik_unos();
$baza = new Baza();
$id = $_GET["id"];
$pogreska ="";
if (!empty($_POST)) {
    $lozinka = md5($_POST["lozinka"]);
    $ponovljenja = md5($_POST["plozinka"]);
    if (strcmp($lozinka, $ponovljenja) == 0 && strlen($lozinka)) {
        $upit = "update korisnici set lozinka = '$lozinka' where id = $id";
        if ($baza->ostali_upiti($upit) == false) {
            echo $upit;
            exit();
        }
        header("Location: index.php");
    } else {
        $pogreska .= "Nisu unesene iste lozinke.";
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

        
        <div class="sadrzaj">
            <div>
                <?php echo $pogreska; ?>
            </div>
            <form id="promjena" name="promjena" method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
                PROMJENA LOZINKE
                <table>
                    <tr>
                        <td>Lozinka:</td>
                        <td><input type="password" id="lozinka" name="lozinka" placeholder="Lozinka"></td>
                    </tr>
                    <tr>
                        <td>Ponovljena lozinka:</td>
                        <td><input type="password" id="plozinka" name="plozinka" placeholder="Ponovljena lozinka"></td>
                    </tr>
                    <tr>
                        <td>
                            <input type="submit" id="potvrda" name="potvrda" value="Promjeni lozinku">
                        </td>
                    </tr>

                </table>
            </form>
        </div>
    </body>
</html>