function provjera_lozinke() {
    var lozinka = document.registracija.lozinka.value;
    var lozinka2 = document.getElementById("lozinka");
    var tekst = document.getElementById("lozka");
    var poseban_znak = 0;
    var veliko_slovo = 0;
    var malo_slovo = 0;
    var broj = 0;
    var znak = '';
    tekst.innerHTML = "";
    if (lozinka2.type !== "password") {
        tekst.innerHTML += "Krivi format za unos lozinke";
        return false;
    }
    if (lozinka === "") {
        tekst.innerHTML += "Lozinka mora biti unesena";
        return false;
    }
    if (lozinka.length < 8) {
        tekst.innerHTML += "Lozinka mora sadržavati najmanje 8 znakova";
        return false;
    }
    return true;
}
function identicnost() {
    var lozinka = document.registracija.lozinka.value;
    var plozinka = document.registracija.p_lozinka.value;
    var lozinka2 = document.getElementById("p_lozinka");
    var tekst = document.getElementById("potvrda");
    tekst.innerHTML = "";
    if (lozinka2.type !== "password") {
        tekst.innerHTML += "Krivi format za unos lozinke";
        return false;
    }
    if (lozinka2 === "") {
        tekst.innerHTML += "Ponovljena lozinka mora biti unesena";
        return false;
    }
    if (lozinka !== plozinka) {
        tekst.innerHTML += "Lozinka i ponovljena lozinka nisu jednake";
        return false;
    }
    return true;
}
function jmbag_test(){
    var patt = /^[0-9]{10}$/;
    var jmbag = document.registracija.jmbag.value;
    var tekst = document.getElementById("jmbag_test");
    tekst.innerHTML = "";
    if(!patt.test(jmbag)){
        tekst.innerHTML +="JMBAG nije ispravno unesen";
        return false;
    }
    return true;
}

function email(){
    var mail = document.registracija.mail.value;
    var patt = /^[^@]+@[^@]+\.[^@]+$/;
    var tekst = document.getElementById("email_verif");
    tekst.innerHTML = "";
    if(!patt.test(mail)){
        tekst.innerHTML +="Mail nije unesen u dobrom obliku";
        return false;
    }
    return true;
}
function kontakt(){
    var kontakt = document.registracija.kontakt.value;
    var tekst = document.getElementById("kont");
    tekst.innerHTML = "";
    if(kontakt === ""){
        tekst.innerHTML += "Kontakt broj mora biti unesen";
        return false;
    }
    return true;
}
function provjera() {
    var greska = true;
    if(provjera_lozinke() === false){
        greska = false;
    }
    if(identicnost() === false){
        greska = false;
    }
    if(jmbag_test() === false){
        greska = false;
    }
    if(email() === false){
        greska = false;
    }
    if(kontakt() === false){
        greska = false;
    }
    if(!greska){
        alert("Određena polja su pogrešno unesena");
        return false;
    }
    
    return true;
}