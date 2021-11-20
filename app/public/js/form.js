function verkehrChange() {
    // Holen das HTML Element

    //Wert des Selects
    var value = document.getElementById('selectVerkehr').value;
    // Div des Input vom Zug
    var zug = document.getElementById('kostenZug');
    // Div des Input vom Auto
    var auto = document.getElementById('kmAuto');
    // Div des Input von den Fahrtkosten Zug
    var zugInput = document.getElementById('zugInput');
    // Div des Input von den KMAnzahl Auto
    var autoInput = document.getElementById('autoInput');

    // Wenn Auto ausgewählt ist, Setzt es das andere auf hidden und ändert den Required status
    if (value == 'Auto') {
        zug.hidden = true;
        zugInput.removeAttribute('required');
        auto.hidden = false;
        autoInput.setAttribute('required', 'true');
    }

    // Wenn Zug ausgewählt ist, Setzt es das andere auf hidden und ändert den Required status
    else {
        zug.hidden = false;
        zugInput.setAttribute('required', 'true');
        auto.hidden = true;
        autoInput.removeAttribute('required');
    }
}
// Wenn das Dokument geladen wird, das richtige Feld anzeigen
$(document).ready(function () {
    verkehrChange();

})