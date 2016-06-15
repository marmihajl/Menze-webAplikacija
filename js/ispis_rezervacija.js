var xmlHttp = createXmlHttpRequestObject();
var m;
var datum = "";
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

function dajPodatke(menza)
{
    datum = document.getElementById("datum").value;
    m = menza.value;
    if (xmlHttp) {
        try {
            if (xmlHttp.readyState === 4 || xmlHttp.readyState === 0) {
                xmlHttp.open("GET", "popis_rezervacija.php", true);
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
    var ogranicen_ispis = 0;
    var inHTML = "";
    for (i = 0; i < nameArray.length; i++) {
        if (m === nameArray[i][4] && datum === nameArray[i][5]) {
            inHTML += 'Meni: ' + nameArray[i][0] + '<br>';
            inHTML += 'Količina: ' + nameArray[i][2] + '<br>';
            inHTML += 'Datum: ' + nameArray[i][5] + '<br>';
            inHTML += 'Vrijeme: ' + nameArray[i][3] + '<br>';
            inHTML += 'Status: ' + nameArray[i][6] + '<br><hr>';
        }
    }
    var oSuggest = document.getElementById("cilj");
    oSuggest.innerHTML = inHTML;
}

function pripremiPodatke(resultsXml)
{
    var resultsArray = new Array();
    for (i = 0; i < resultsXml.length; i++) {
        var $meni = resultsXml.item(i).getAttribute("meni");
        var $korisnik = resultsXml.item(i).getAttribute("korisnik");
        var $kolicina = resultsXml.item(i).getAttribute("kolicina");
        var $vrijeme = resultsXml.item(i).getAttribute("vrijeme");
        var $menza = resultsXml.item(i).getAttribute("menza");
        var $datum = resultsXml.item(i).getAttribute("datum");
        var $status = resultsXml.item(i).getAttribute("status");
        var $podaci = new Array($meni, $korisnik, $kolicina, $vrijeme, $menza, $datum, $status);
        resultsArray[i] = $podaci;
    }
    return resultsArray;
}