var xmlHttp = createXmlHttpRequestObject();
var tekst = "";
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

function tagSlika(slika)
{
    tekst = slika.value;
    if (xmlHttp) {
        try {
            if (xmlHttp.readyState === 4 || xmlHttp.readyState === 0) {
                xmlHttp.open("GET", "popis_slika.php", true);
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
    var image = "";
    var img = "";
    var string = "";
    var inHTML = "";
    if (tekst !== "") {
        for (i = 0; i < nameArray.length; i++) {
            var string = nameArray[i][2];
            string = string.split(";");
            if (string.length > 0) {
                for (j = 0; j < string.length; j++) {
                    if (string[j] === tekst) {
                        image = nameArray[i][1];
                        img = nameArray[i][0] + "/" + image;
                        inHTML += '<img src= "' + img + '" width="250" height="250"><span>       </span>';
                    }
                }
            }

        }
    } else {
        for (i = 0; i < nameArray.length; i++) {

            image = nameArray[i][1];
            img = nameArray[i][0] + "/" + image;
            inHTML += '<img src= "' + img + '" width="250" height="250"><span>       </span>';
        }
    }
    var oSuggest = document.getElementById("cilj");
    oSuggest.innerHTML = inHTML;
}

function pripremiPodatke(resultsXml)
{
    var resultsArray = new Array();
    for (i = 0; i < resultsXml.length; i++) {
        var $korisnik = resultsXml.item(i).getAttribute("korisnik");
        var $naziv = resultsXml.item(i).getAttribute("naziv");
        var $tag = resultsXml.item(i).getAttribute("tag");
        var $podaci = new Array($korisnik, $naziv, $tag);
        resultsArray[i] = $podaci;
    }
    return resultsArray;
}