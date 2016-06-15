<?php

class Sesija {

    const KORISNIK = "korisnik";

    static function kreirajSesiju() {

        if (session_id() == "") {
            session_start();
        }
    }

    static function kreirajKorisnika($korisnik) {
        self::kreirajSesiju();
        $_SESSION[self::KORISNIK] = $korisnik;
    }


    static function dajKorisnika() {
        self::kreirajSesiju();
        if (isset($_SESSION[self::KORISNIK])) {
            $korisnik = $_SESSION[self::KORISNIK];
        } else {
            return null;
        }
        return $korisnik;
    }


    static function obrisiSesiju() {
        session_unset();
        session_destroy();
    }

}
