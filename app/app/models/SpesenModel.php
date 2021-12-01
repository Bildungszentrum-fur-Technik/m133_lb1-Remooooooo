<?php

/**
 * Definition der SpesenModel Attribute
 * 
 * id                   Auto Increment         ID des Spesenformular, wird referenziert werden von den Formularen
 * Personalnummer       String                 6 Stellig (Nur Zahlen von 0-9)
 * Datum                String                 Datum Formatierung
 * Reiseziel            String                 Ort der Reise
 * Essenskosten         int                    max. 50 CHF
 * Fahrtkosten          int                    Zugticketkosten
 * km anzahl            int                    km Anzahl mit Auto
 * uebernachtung        int                    Kosten Uebernachtung
 * Verkehrsmittel       String                 Dropdown mit Values Zug und Auto
 * Unterschrift         boolean                Genehmigen oder Ablehenen
 * Quittungen           file                   Upload für Quittungen
 */
class SpesenModel extends BaseModel
{
    private $id; // War nicht in der Doku, wurde nachträglich hinzugefügt
    private $personalnummer;
    private $datum;
    private $reiseziel;
    private $essenskosten;
    private $fahrtkosten;
    private $kmanzahl;
    private $uebernachtung;
    private $verkehrsmittel;
    private $unterschrift;
    private $quittungen;

    /**
     * Funktion des Vorgesetzten, welche alle Daten ausgibt
     */
    public function getSpesenAlle()
    {
        $this->db->query("SELECT * FROM SpesenFormular");

        $results = $this->db->resultSet();

        return $results;
    }

    /**
     * Überprüft die User ID sucht alle Formulare von diesem User auf der DB und returnt diese
     * User ID wurde nachträglich hinzugefügt, da es vorkommen kann, dass jemand anderes für einen User ein Formular ausfüllt
     */
    public function getSpesenUser($userId)
    {
        $this->db->query("SELECT * FROM SpesenFormular WHERE UserID = :userid");
        $this->db->bind(':userid', $userId);
        $results = $this->db->resultSet();

        return $results;
    }

    /**
     * Ruft Daten des Ausgewählten Formulars auf, um es zu Unterschreiben
     */
    public function getSpesenById($id)
    {
        $this->db->query("SELECT * FROM SpesenFormular WHERE ID = :id");
        $this->db->bind(':id', $id);
        $results = $this->db->resultSet();

        return $results;
    }

    // Funktion zum Spesenformular abschicken
    public function addSpesen($data, $userId)
    {

        $this->db->query("INSERT INTO SpesenFormular ( `Personalnummer`, `UserId`, `Datum`, `Reiseziel`, `Essenskosten`, `Fahrtkosten`, `KM_Anzahl`, `Uebernachtung`, `Verkehrsmittel`) VALUES(
            :personalnummer , :userid ,:datum ,:reiseziel ,:essenskosten ,:fahrtkosten ,:km_anzahl ,:uebernachtung ,:verkehrsmittel )");
        $this->db->bind(':personalnummer', $data['personalnummer']);
        $this->db->bind(':userid', $userId);
        $this->db->bind(':datum', $data['datum']);
        $this->db->bind(':reiseziel',  $data['reiseziel']);
        $this->db->bind(':essenskosten',  $data['essenskosten']);
        $this->db->bind(':fahrtkosten',  $data['fahrtkosten']);
        $this->db->bind(':km_anzahl',  $data['kmanzahl']);
        $this->db->bind(':uebernachtung',  $data['uebernachtung']);
        $this->db->bind(':verkehrsmittel',  $data['verkehrsmittel']);
        return $this->db->execute();
    }

    // Spesenformular genehmigen
    // Setzt die Unterschrift auf true
    public function genehmigenSpesen($id)
    {
        $this->db->query("UPDATE SpesenFormular SET Unterschrift = 1 WHERE ID = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    // Spesenformular ablehnen
    // Falls ein Spesenformular abgelehnt wird, wird es gelöscht.
    public function ablehnenSpesen($id)
    {
        $spese = $this->getSpesenById($id);
        if (
            count($spese) > 0 // Überprüft ob Formular existiert
            &&
            // Falls das Formular bereits genehmigt wurde, return false, damit ein genehmigtes Formular nicht gelöscht werden kann
            !$spese[0]['Unterschrift']
        ) {
            $this->db->query("DELETE FROM SpesenFormular WHERE ID = :id");
            $this->db->bind(':id', $id);
            return $this->db->execute();
        }
        return false;
    }
}
