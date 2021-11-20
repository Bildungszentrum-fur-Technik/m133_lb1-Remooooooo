<?php

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
        // Instanzierung Model
        $SpesenModel = $this->model('SpesenModel');
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            // Daten trimmen (Leerzeichen entfernen usw.) und Sanitizen (überprüfen auf gültigkeit)
            $personalnummer = trim(
                filter_input(INPUT_POST, 'personalnummer', FILTER_SANITIZE_NUMBER_INT)
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

            ];

            // Daten validieren
            if (empty($data['personalnummer'])) {
                $data['personalnummer_err'] = 'Bitte Personalnummer angeben';
            } elseif ($data['personalnummer'] < 100000 or $data['personalnummer'] > 999999) {
                $data['personalnummer_err'] = 'Personalnummer ungültig';
            }

            if (empty($data['datum'])) {
                $data['datum_err'] = 'Bitte Datum angeben';
            }

            if (empty($data['reiseziel'])) {
                $data['reiseziel_err'] = 'Bitte Reiseziel auswählen';
            }
            if (empty($data['essenskosten'])) {
                $data['essenskosten_err'] = 'Bitte Essenskosten angeben';
            } else if ($data['essenskosten'] < 0 or $data['essenskosten'] > 50) {
                $data['essenskosten_err'] = 'Essenskosten müssen zwischen 0 und 50 Franken sein';
            }

            if (empty($data['verkehrsmittel'])) {
                $data['verkehrsmittel_err'] = 'Bitte Verkehrsmittel auswählen';
            }

            // Überprüfen ob Auto oder Zug ausgewählt ist und ob die Mussfelder ausgefüllt sind
            if ($data['verkehrsmittel'] == 'Auto') {
                if (empty($data['kmanzahl'])) {
                    $data['kmanzahl_err'] = 'Bitte Kilometeranzahl angeben';
                }
            } else if ($data['verkehrsmittel'] == 'Zug') {
                if (empty($data['fahrtkosten'])) {
                    $data['fahrtkosten_err'] = 'Bitte Fahrtkosten angeben';
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
                && empty($data['essenskosten_err']) && empty($data['verkehrsmittel_err']) && empty($data['fahrtkosten_err']) && empty($data['fahrtkosten_err']) && empty($data['kmanzahl_err'])
            ) {
                // Alles gut, keine Fehler vorhanden
                // Späteres TODO: Auf DB schreiben

                $SpesenModel->fakewriteData($data);
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
