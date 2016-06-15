var xmlHttp = createXmlHttpRequestObject();
var odabrana_menza = "";
var korisnik_prijavljen = false;
var datum = "";
var datum_sustava = "";
function createXmlHttpRequestObject()
{
    var xmlHttp;
    try {
        xmlHttp = new XMLHttpRequest();
    } catch (e) {
        var XmlHttpVersions = new Array('MSXML2.XmlHttp', 'Microsoft.XmlHttp');
        for (var i = 0; i < XmlHttpVersions.length && !xmlHttp; i++) {
            try {
                xmlHttp = new ActiveXObject(XmlHttpVersions[i]);
            } catch (e) {
            }
        }
    }
    if (!xmlHttp)
        alert("Problem kod kreiranja XMLHttpRequest objekta.");
    else
        return xmlHttp;
}

function dajPodatke(menza, prijavljen)
{
    odabrana_menza = menza.value;
    korisnik_prijavljen = prijavljen;
    datum = document.getElementById("datum").value;
    if (xmlHttp) {
        try {
            if (xmlHttp.readyState === 4 || xmlHttp.readyState === 0) {
                xmlHttp.open("GET", "popis_meni.php", true);
                xmlHttp.onreadystatechange = preuzmiPodatke;
                xmlHttp.send(null);
            } else {  // ako je XMLHttpRequest objekt zauzet...
            }
        } catch (e) {
            alert("Problem kod povezivanja na server:\n" + e.toString());
        }
    }
}

function preuzmiPodatke()
{
    if (xmlHttp.readyState === 4) {
        if (xmlHttp.status === 200) { // samo ako je HTTP status "OK"
            try {
                prikaziPodatke();
            } catch (e)
            {
                alert(e.toString());
            }
        } else {
            alert("Problem kod preuzimanja podataka:\n" + xmlHttp.statusText);
        }
    }
}

function prikaziPodatke()
{
    var response = xmlHttp.responseText;

    if (response.indexOf("ERRNO") >= 0
            || response.indexOf("error") >= 0
            || response.length === 0)
        throw(response.length === 0 ? "Prazan odgovor servera." : response);

    response = xmlHttp.responseXML.documentElement;

    var nameArray = new Array();
    nameArray = pripremiPodatke(response.getElementsByTagName("name"));

    if (korisnik_prijavljen)
    {
        var inHTML = "<a href=like.php?svida=1&atr=1&id=" + odabrana_menza + ">Svida mi se</a><span>   </span><a href=like.php?svida=0&atr=1&id=" + odabrana_menza + ">Ne svida mi se</a><br>*za rezervaciju određenog menija stisnite na naziv menija koji želite rezervirati<br>";
    } else {
        var inHTML = "";
    }
    var ogranicen_ispis = 0;
    for (i = 0; i < nameArray.length; i++) {
        if (nameArray[i][8] === odabrana_menza && datum === nameArray[i][3]) {
            if (korisnik_prijavljen) {
                inHTML += 'Naziv: ' + '<a href="rezervacija.php?id=' + nameArray[i][0] + '">' + nameArray[i][1] + '</a>';
                inHTML += '<span>  </span><a href="like.php?svida=1&atr=0&id=' + nameArray[i][0] + '">Svida mi se</a>';
                inHTML += '<span>  </span><a href="like.php?svida=0&atr=0&id=' + nameArray[i][0] + '">Ne svida mi se</a><br>';
                inHTML += 'Opis: ' + nameArray[i][2] + '<br>';
                inHTML += 'Datum: ' + nameArray[i][3] + '<br>';
                inHTML += 'Raspoloživa količina: ' + nameArray[i][5] + '<br><hr>';
            } else {
                if (ogranicen_ispis < 3) {
                    inHTML += 'Naziv: ' + nameArray[i][1] + '<br>';
                    inHTML += 'Opis: ' + nameArray[i][2] + '<br>';
                    inHTML += 'Datum: ' + nameArray[i][3] + '<br>';
                    inHTML += 'Raspoloživa količina: ' + nameArray[i][5] + '<br><hr>';
                    ogranicen_ispis++;
                }
            }
        }

    }
    var oSuggest = document.getElementById("cilj");
    oSuggest.innerHTML = inHTML;
}

function pripremiPodatke(resultsXml)
{
    var resultsArray = new Array();
    for (i = 0; i < resultsXml.length; i++) {
        var $id = resultsXml.item(i).getAttribute("id");
        var $naziv = resultsXml.item(i).getAttribute("naziv");
        var $opis = resultsXml.item(i).getAttribute("opis");
        var $datum = resultsXml.item(i).getAttribute("datum");
        var $pocetno = resultsXml.item(i).getAttribute("pocetno");
        var $trenutno = resultsXml.item(i).getAttribute("trenutno");
        var $svida = resultsXml.item(i).getAttribute("svida");
        var $nesvida = resultsXml.item(i).getAttribute("nesvida");
        var $menza = resultsXml.item(i).getAttribute("menza");
        var $podaci = new Array($id, $naziv, $opis, $datum, $pocetno, $trenutno, $svida, $nesvida, $menza);
        resultsArray[i] = $podaci;
    }
    return resultsArray;
}