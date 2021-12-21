<?php

/**
 * Controller für das Spesenformular
 */
class SpesenController extends Controller
{
    /**
     * Index funktion, welche die Funktionaloitäten des Controllers beinhaltet.
     *
     * @param -
     *
     * @return void
     */
    public function index()
    {
        // Dürfen wir überhaupt diese Funktion nutzen? 
        if (!isset($_SESSION['user_id'])) {
            // Kein Login -> Weiterleitung auf Login
            redirect('Users/login');
        } else {

            // Instanzierung Model
            $SpesenModel = $this->model('SpesenModel');
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                // Daten trimmen (Leerzeichen entfernen usw.) und Sanitizen (überprüfen auf gültigkeit)
                // Personalnummer ist nun ein String und kein INT, da auch führende Nullen möglich sind
                $personalnummer = trim(
                    filter_input(INPUT_POST, 'personalnummer', FILTER_SANITIZE_STRING)
                );
                $datum = trim(
                    filter_input(INPUT_POST, 'datum', FILTER_SANITIZE_STRING)
                );
                $reiseziel = trim(
                    filter_input(INPUT_POST, 'reiseziel', FILTER_SANITIZE_STRING)
                );
                $essenskosten = trim(
                    filter_input(INPUT_POST, 'essenskosten', FILTER_SANITIZE_NUMBER_INT)
                );
                $fahrtkosten = trim(
                    filter_input(INPUT_POST, 'fahrtkosten', FILTER_SANITIZE_NUMBER_INT)
                );
                $kmanzahl = trim(
                    filter_input(INPUT_POST, 'kmanzahl', FILTER_SANITIZE_NUMBER_INT)
                );
                $uebernachtung = trim(
                    filter_input(INPUT_POST, 'uebernachtung', FILTER_SANITIZE_NUMBER_INT)
                );
                $verkehrsmittel = trim(
                    filter_input(INPUT_POST, 'verkehrsmittel', FILTER_SANITIZE_STRING)
                );

                $data = [
                    'personalnummer' => $personalnummer,          // Form-Feld-Daten
                    'personalnummer_err' => '',      // Feldermeldung für Attribute
                    'datum' => $datum,       // Form-Feld-Daten
                    'datum_err' => '',    // Feldermeldung für Attribute
                    'reiseziel' => $reiseziel,    // Form-Feld-Daten
                    'reiseziel_err' => '',    // Feldermeldung für Attribute
                    'essenskosten' => $essenskosten,    // Form-Feld-Daten
                    'essenskosten_err' => '',    // Feldermeldung für Attribute
                    'fahrtkosten' => $fahrtkosten,    // Form-Feld-Daten
                    'fahrtkosten_err' => '',    // Feldermeldung für Attribute,    
                    'kmanzahl' => $kmanzahl,    // Form-Feld-Daten
                    'kmanzahl_err' => '',    // Feldermeldung für Attribute
                    'uebernachtung' => $uebernachtung,    // Form-Feld-Daten
                    'uebernachtung_err' => '',    // Feldermeldung für Attribute
                    'verkehrsmittel' => $verkehrsmittel,    // Form-Feld-Daten
                    'verkehrsmittel_err' => '',    // Feldermeldung für Attribute
                    'quittungen' => '',    // Form-Feld-Daten
                    'erstellen_err' => '' // Feldermeldung für Fehlermeldung beim erstellen

                ];
                // Überprüft das Pattern der Personalnummer, ob 6x eine Zahl von 0-9 vorkommt
                $pattern = "/[0-9]{6}/";

                // Daten validieren
                if (empty($data['personalnummer'])) {
                    $data['personalnummer_err'] = 'Bitte Personalnummer angeben';
                } elseif (preg_match($pattern, $data['personalnummer']) == 0) {
                    $data['personalnummer_err'] = 'Personalnummer ungültig';
                }


                if (empty($data['reiseziel'])) {
                    $data['reiseziel_err'] = 'Bitte Reiseziel auswählen';
                }

                // Pattern für den Regex, ob das Datum valide ist
                $patterndate = "/^\d{4}-(0[1-9]|1[0-2])-(0[1-9]|[12][0-9]|3[01])$/";

                if (empty($data['datum'])) {
                    $data['datum_err'] = 'Bitte Datum angeben';
                }
                // Überprüft, ob das Datum in einem zu langen Zeitraum zurück liegt oder in der Zukunft liegt.
                elseif (strtotime($data['datum']) < strtotime('-6 month')) {
                    $data['datum_err'] = 'Bitte gültiges Datum eingeben';
                } elseif (strtotime($data['datum']) > strtotime('+6 month')) {
                    $data['datum_err'] = 'Bitte gültiges Datum eingeben';
                }

                // Überprüft das Pattern des Datum 
                elseif (preg_match($patterndate, $data['datum']) == 0) {
                    $data['datum_err'] = 'Datumsformat ungültig';
                }

                // Essenskosten müssen zwischen 0 und 50 CHF
                if (empty($data['essenskosten'])) {
                    $data['essenskosten_err'] = 'Bitte Essenskosten angeben';
                } else if ($data['essenskosten'] < 0 or $data['essenskosten'] > 50) {
                    $data['essenskosten_err'] = 'Essenskosten müssen zwischen 0 und 50 Franken sein';
                }

                // Validierung ergänzt, darf kein negativer Wert sein
                if (empty($data['uebernachtung'])) {
                    $data['uebernachtung_err'] = 'Bitte Uebernachtungskosten angeben';
                } else if ($data['uebernachtung'] < 0) {
                    $data['uebernachtung_err'] = 'Uebernachtungskosten dürfen nicht unter 0 Franken liegen';
                }

                if (empty($data['verkehrsmittel'])) {
                    $data['verkehrsmittel_err'] = 'Bitte Verkehrsmittel auswählen';
                }

                // Überprüfen ob Auto oder Zug ausgewählt ist und ob die Mussfelder ausgefüllt sind
                // Validierung ergänzt, darf kein negativer Wert sein
                if ($data['verkehrsmittel'] == 'Auto') {
                    if (empty($data['kmanzahl'])) {
                        $data['kmanzahl_err'] = 'Bitte Kilometeranzahl angeben';
                    } else if ($data['kmanzahl'] < 0) {
                        $data['kmanzahl_err'] = 'KM Anzahl darf nicht unter 0 KM liegen';
                    }

                    // Validierung ergänzt, darf kein negativer Wert sein
                } else if ($data['verkehrsmittel'] == 'Zug') {
                    if (empty($data['fahrtkosten'])) {
                        $data['fahrtkosten_err'] = 'Bitte Fahrtkosten angeben';
                    } else if ($data['fahrtkosten'] < 0) {
                        $data['fahrtkosten_err'] = 'Fahrtkosten dürfen nicht unter 0 Franken liegen';
                    }
                } else {
                    $data['verkehrsmittel_err'] = 'Bitte Verkehrsmittel auswählen';
                }

                //Dateiupload der Quittung
                $uploaddir = '../data/';
                $uploadfile = $uploaddir . basename($_FILES['quittungen']['name']);

                if (!empty($_FILES['quittungen'])) {
                    if (move_uploaded_file($_FILES['quittungen']['tmp_name'], $uploadfile)) {
                        $data['quittungen'] = $uploadfile;
                    }
                }

                // Keine Errors vorhanden
                if (
                    empty($data['personalnummer_err']) && empty($data['datum_err']) && empty($data['reiseziel_err'])
                    && empty($data['essenskosten_err']) && empty($data['verkehrsmittel_err']) && empty($data['fahrtkosten_err']) && empty($data['fahrtkosten_err']) && empty($data['kmanzahl_err']) && empty($data['uebernachtung_err'])
                ) {
                    // Alles gut, keine Fehler vorhanden
                    // Falls Zug oder Ausgewählt wird, wird der andere Wert auf 0 gesetzt
                    if (empty($data['kmanzahl'])) {
                        $data['kmanzahl'] = 0;
                    }
                    if (empty($data['fahrtkosten'])) {
                        $data['fahrtkosten'] = 0;
                    }
                    // Fehlermeldung falls das Erstellen nicht funktiniert. 
                    $result = $SpesenModel->addSpesen($data, $_SESSION["user_id"]);
                    if (!$result) {
                        $data['erstellen_err']  = 'Beim Erstellen ist ein Fehler aufgetreten.';
                        echo $this->twig->render('spesen/index.twig.html', ['title' => 'Spesen Add', 'urlroot' => URLROOT, 'data' => $data]);
                        // Ansonsten Redirect auf Homepage
                    } else {
                        header("Location: /");
                        echo "Redirect";
                    }
                } else {
                    // Fehler vorhanden - Fehler anzeigen
                    // View laden mit Fehlern

                    echo $this->twig->render('spesen/index.twig.html', ['title' => 'Spesen Add', 'urlroot' => URLROOT, 'data' => $data]);
                }

                //
            } else {

                // Init Form mit Default-Daten, weil Get-Aufruf
                $data = [
                    'personalnummer' => '',          // Form-Feld-Daten
                    'personalnummer_err' => '',      // Feldermeldung für Attribute
                    'datum' => '',       // Form-Feld-Daten
                    'datum_err' => '',    // Feldermeldung für Attribute
                    'reiseziel' => '',    // Form-Feld-Daten
                    'reiseziel_err' => '',    // Feldermeldung für Attribute
                    'essenskosten' => '',    // Form-Feld-Daten
                    'essenskosten_err' => '',    // Feldermeldung für Attribute
                    'fahrtkosten' => '',    // Form-Feld-Daten
                    'fahrtkosten_err' => '',    // Feldermeldung für Attribute,    
                    'kmanzahl' => '',    // Form-Feld-Daten
                    'kmanzahl_err' => '',    // Feldermeldung für Attribute
                    'uebernachtung' => '',    // Form-Feld-Daten
                    'uebernachtung_err' => '',    // Feldermeldung für Attribute
                    'verkehrsmittel' => '',    // Form-Feld-Daten
                    'verkehrsmittel_err' => '',    // Feldermeldung für Attribute
                ];
                echo $this->twig->render('spesen/index.twig.html', ['title' => "Spesen - Add", 'urlroot' => URLROOT, 'data' => $data]);
            }
        }
    }
}
