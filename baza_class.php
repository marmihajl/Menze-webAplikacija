<?php

class Baza {

    const posluzitelj = "localhost";
    const korisnik = "WebDiP2015x054";
    const lozinka = "admin_YTra";
    const baza = "WebDiP2015x054";

    public function spoji() {
        $veza =new mysqli(self::posluzitelj, self::korisnik, self::lozinka, self::baza);
        if ($veza->connect_errno) {
            echo "Greska " . $veza->connect_errno . " - " . $veza->connect_error;
            return null;
        }
        $veza->set_charset("utf8");
        return $veza;
    }

    public function prekini_vezu() {
        $veza->close();
    }

    public function select_upit($upit) {
        $veza = $this->spoji();
        if ($veza == null) {
            echo "Greska s bazom.";
            return null;
        } 
        $rezultat = $veza->query($upit);
        return $rezultat;
    }
    public function ostali_upiti($upit) {
        $veza = $this->spoji();
        if ($veza == null) {
            echo "Greska s bazom.";
            return null;
        } 
        $rezultat = $veza->query($upit);
        if($rezultat == 1){
            return true;
        }
        else{
            return false;
        }
    } 

}