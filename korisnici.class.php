<?php
include_once './vrijeme.php';
class Korisnik {

    var $id="";
    var $korisnicko_ime = "";
    var $tip_korisnika = "";
    var $jmbag = "";
    var $mail = "";
    var $datumIVrijeme = "";

    function dodavanjeKorisnika($id, $korisnik, $tip, $jmbag, $mail) {
        $this->id = $id;
        $this->korisnicko_ime = $korisnik;
        $this->tip_korisnika = $tip;
        $this->jmbag = $jmbag;
        $this->mail = $mail;
        $this->datumIVrijeme = vrijeme();
        return array("id" => $this->id,
            "korisnicko_ime" => $this->korisnicko_ime,
            "tip_korisnika" => $this->tip_korisnika,
            "jmbag" => $this->jmbag,
            "email" => $this->mail,
            "datum" => $this->datumIVrijeme);
    }

    
}
?>