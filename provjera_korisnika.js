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

function dajPodatke()
{
    if (xmlHttp) {
        try {
            if (xmlHttp.readyState == 4 || xmlHttp.readyState == 0) {
                xmlHttp.open("GET", "korisnici.php", true);
                xmlHttp.onreadystatechange = preuzmiPodatke;
                xmlHttp.send(null);
            } else {
            }
        } catch (e) {
            alert("Problem kod povezivanja na server:\n" + e.toString());
        }
    }
}

function preuzmiPodatke()
{
    if (xmlHttp.readyState === 4) {
        if (xmlHttp.status === 200) {
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

function provjera_korisnika()
{
    var response = xmlHttp.responseText;
    var uneseni = document.getElementById("korisnicko_ime").value;
    var postoji = false;
    if (response.indexOf("ERRNO") >= 0
            || response.indexOf("error") >= 0
            || response.length === 0)
        throw(response.length === 0 ? "Prazan odgovor servera." : response);

    response = xmlHttp.responseXML.documentElement;

    var nameArray = new Array();
    nameArray = pripremiPodatke(response.getElementsByTagName("name"));

    for (i = 0; i < nameArray.length; i++) {
        if (nameArray[i] === uneseni) {
            postoji = true;
        }
    }
    if (postoji) {
        var obavjest = document.getElementById("korisnik_greska");
        obavjest.innerHTML = inHTML;
        return false;
    }
    return true;
}

function pripremiPodatke(resultsXml)
{
    var resultsArray = new Array();
    for (i = 0; i < resultsXml.length; i++) {
        var $korisnik = resultsXml.item(i).getAttribute("kor");
        resultsArray[i] = $korisnik;
    }
    return resultsArray;
}